<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\Pendaftaran;
use App\Models\RequestWorkshop;
use App\Models\Absensi;
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
                'tanggal_daftar' => Carbon::now('Asia/Jakarta')
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

    public function myWorkshopDetail($workshop_id)
    {
        $user_id = Auth::id();
        
        // Cek apakah user terdaftar pada workshop ini
        $pendaftaran = Pendaftaran::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('pengguna.my-workshop')
                ->with('error', 'Anda tidak terdaftar pada workshop ini');
        }

        // Ambil workshop dengan relasi
        $workshop = Workshop::where('workshop_id', $workshop_id)
            ->with(['pemateri', 'keywords', 'materi'])
            ->first();

        if (!$workshop) {
            return redirect()->route('pengguna.my-workshop')
                ->with('error', 'Workshop tidak ditemukan');
        }

        // Cek apakah user sudah absensi
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
                ? 'Absensi tersedia. Sisa waktu: ' . $remainingMinutes . ' menit'
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

        return view('User.myworkshopdetail', compact(
            'workshop', 
            'pendaftaran', 
            'absensi',
            'canTakeAttendance',
            'attendanceMessage',
            'hasAttended',
            'attendanceStatus',
            'startTime',
            'endTime'
        ));
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

    public function downloadCertificate($workshop_id)
    {
        $user_id = Auth::id();
        
        // Cek apakah user terdaftar
        $pendaftaran = Pendaftaran::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$pendaftaran) {
            return redirect()->route('pengguna.my-workshop')
                ->with('error', 'Anda tidak terdaftar pada workshop ini');
        }

        // Cek apakah sudah absensi
        $absensi = Absensi::where('workshop_id', $workshop_id)
            ->where('user_id', $user_id)
            ->first();

        if (!$absensi) {
            return redirect()->route('pengguna.my-workshop.detail', $workshop_id)
                ->with('error', 'Sertifikat hanya tersedia untuk peserta yang sudah mengikuti absensi workshop');
        }

        // Ambil workshop dan user
        $workshop = Workshop::where('workshop_id', $workshop_id)
            ->with('pemateri')
            ->first();

        if (!$workshop) {
            return redirect()->route('pengguna.my-workshop')
                ->with('error', 'Workshop tidak ditemukan');
        }

        $user = Auth::user();

        try {
            // Generate sertifikat
            $certificatePath = $this->generateCertificate($workshop, $user, $absensi);
            
            // Download file
            return response()->download($certificatePath, 'Sertifikat_' . $workshop->judul . '_' . $user->nama . '.png')
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error generating certificate', [
                'user_id' => $user_id,
                'workshop_id' => $workshop_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('pengguna.my-workshop.detail', $workshop_id)
                ->with('error', 'Gagal membuat sertifikat: ' . $e->getMessage());
        }
    }

    private function generateCertificate($workshop, $user, $absensi)
    {
        // Path template sertifikat (coba PNG dulu, lalu JPG)
        $templatePath = public_path('images/certificate-template.png');
        $templateJpgPath = public_path('images/certificate-template.jpg');
        
        // Cek file template
        if (!file_exists($templatePath) && !file_exists($templateJpgPath)) {
            throw new \Exception('Template sertifikat tidak ditemukan. Silakan letakkan file certificate-template.png atau certificate-template.jpg di folder public/images/');
        }

        // Baca template image (coba PNG dulu, lalu JPG)
        $image = null;
        if (file_exists($templatePath)) {
            $image = imagecreatefrompng($templatePath);
        } elseif (file_exists($templateJpgPath)) {
            $image = imagecreatefromjpeg($templateJpgPath);
        }
        
        if (!$image) {
            throw new \Exception('Gagal membaca template sertifikat. Pastikan file format PNG atau JPG valid.');
        }

        // Set warna untuk teks (sesuai template sertifikat)
        // Warna hijau untuk nama - disesuaikan dengan template (dark green seperti di sertifikat)
        $darkGreen = imagecolorallocate($image, 0, 80, 0); // Dark green untuk nama (disesuaikan dengan template)
        $lightGreen = imagecolorallocate($image, 34, 139, 34); // Lighter green alternatif
        // Warna untuk teks detail/penghargaan - hitam atau dark gray sesuai template
        $textBlack = imagecolorallocate($image, 0, 0, 0); // Hitam untuk teks detail (sesuai template)
        $darkGray = imagecolorallocate($image, 30, 30, 30); // Dark gray alternatif
        
        // Ambil dimensi gambar
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Debug: Log dimensi gambar untuk troubleshooting
        Log::info('Certificate generation', [
            'width' => $width,
            'height' => $height,
            'template_path' => file_exists($templatePath) ? $templatePath : $templateJpgPath
        ]);

        // Font settings - cari font Kugile untuk nama (prioritas), lalu font script lainnya
        // Juga cari font dari Windows jika tersedia
        $windowsFontPath = 'C:\Windows\Fonts';
        
        $scriptFontPaths = [
            public_path('fonts/kugile.ttf'), // PRIORITAS - Font Kugile
            public_path('fonts/Kugile.ttf'), // Case sensitive alternatif
            public_path('fonts/kugile.TTF'), // Case sensitive alternatif
            $windowsFontPath . '\BRUSHSCI.TTF', // Brush Script dari Windows
            $windowsFontPath . '\BRITANIC.TTF', // Britannic dari Windows
            public_path('fonts/brush-script.ttf'),
            public_path('fonts/lucida-calligraphy.ttf'),
            public_path('fonts/comic-sans.ttf'), // Fallback
            public_path('fonts/arial.ttf'), // Fallback
        ];
        
        // Font standar untuk teks lainnya - juga cari dari Windows
        $standardFontPaths = [
            public_path('fonts/times-new-roman.ttf'),
            $windowsFontPath . '\times.ttf', // Times New Roman dari Windows
            $windowsFontPath . '\TIMES.TTF', // Times New Roman dari Windows
            $windowsFontPath . '\ARIAL.TTF', // Arial dari Windows
            $windowsFontPath . '\CALIBRI.TTF', // Calibri dari Windows
            public_path('fonts/times.ttf'),
            public_path('fonts/arial.ttf'),
            public_path('fonts/calibri.ttf'),
        ];
        
        $scriptFontPath = null;
        foreach ($scriptFontPaths as $path) {
            if (file_exists($path)) {
                $scriptFontPath = $path;
                break;
            }
        }
        
        $standardFontPath = null;
        foreach ($standardFontPaths as $path) {
            if (file_exists($path)) {
                $standardFontPath = $path;
                break;
            }
        }
        
        // Jika tidak ada font custom, gunakan built-in (tapi kurang bagus)
        $useScriptTTF = $scriptFontPath !== null;
        $useStandardTTF = $standardFontPath !== null;
        $fontPath = $standardFontPath ?: $scriptFontPath; // Fallback

        // Data untuk sertifikat
        $namaPeserta = strtoupper($user->nama);
        $judulWorkshop = $workshop->judul;
        $tanggalWorkshop = Carbon::parse($workshop->tanggal)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y');
        $waktuWorkshop = $workshop->waktu ? date('H:i', strtotime($workshop->waktu)) . ' WIB' : '';
        $lokasiWorkshop = $workshop->lokasi ?: 'Lokasi Workshop';
        
        // Teks penghargaan lengkap
        $teksPenghargaan = "Sebagai penghargaan atas berpartisipasinya dalam workshop \"" . $judulWorkshop . "\" yang berlangsung pada " . $tanggalWorkshop . ($waktuWorkshop ? ' Pukul ' . $waktuWorkshop : '') . " di " . $lokasiWorkshop;
        
        // Teks di bawah garis hijau (contoh: "HIB di seminar pkm" - disesuaikan dengan lokasi)
        $teksBawahGaris = "HIB di " . $lokasiWorkshop;

        // Posisi teks (sesuaikan dengan template sertifikat yang sebenarnya)
        // Posisi untuk nama (di bawah "Diberikan kepada:" - sesuai template)
        $namaY = $height * 0.45; // Sekitar 38% dari tinggi (posisi nama di template)
        
        // Posisi untuk teks penghargaan (di bawah nama - sesuai template)
        $penghargaanY = $height * 0.50; // Sekitar 48% dari tinggi (posisi teks detail di template)
        
        // Posisi garis hijau (di bawah teks penghargaan - jika ada)
        $garisY = $height * 0.55; // Sekitar 55% dari tinggi
        
        // Posisi teks di bawah garis hijau (jika ada)
        $teksBawahY = $height * 0.62; // Sekitar 62% dari tinggi

        // Tulis nama peserta dengan font Kugile - ukuran SANGAT BESAR (METODE BARU)
        if ($useScriptTTF) {
            // Hitung ukuran font berdasarkan DPI dan resolusi template
            // Untuk template 2480x3508 (A4 300dpi), gunakan ukuran yang sangat besar
            // DPI biasanya 96 untuk screen, tapi untuk print bisa 300-600
            // Ukuran font dalam pixel = (ukuran pt / 72) * DPI
            
            // METODE BARU: Ukuran font harus benar-benar besar untuk resolusi tinggi
            // imagettftext menggunakan ukuran dalam point yang dikonversi ke pixel
            // Untuk template resolusi tinggi, ukuran font harus sangat besar
            
            // Gunakan ukuran yang sangat besar secara langsung
            // Untuk template 3508px tinggi, nama harus ~15-20% dari tinggi = 500-700px
            $fontSizeNama = intval($height * 0.18); // 18% dari tinggi = SANGAT BESAR
            
            // Pastikan minimal 600px untuk nama (SANGAT BESAR - DIPERBESAR LAGI)
            if ($fontSizeNama < 600) {
                $fontSizeNama = 600; // Minimal 600px - SANGAT BESAR
            }
            
            // Maksimal 1000px untuk template sangat besar
            if ($fontSizeNama > 1000) {
                $fontSizeNama = 1000;
            }
            
            Log::info('Calculated font size for name', [
                'height' => $height,
                'calculated_percent' => intval($height * 0.18),
                'final_size' => $fontSizeNama
            ]);
            
            // Test apakah font bisa digunakan dengan ukuran ini
            $testBbox = @imagettfbbox($fontSizeNama, 0, $scriptFontPath, 'Test');
            if ($testBbox === false) {
                // Jika gagal, kurangi ukuran
                $fontSizeNama = 250;
                $testBbox = @imagettfbbox($fontSizeNama, 0, $scriptFontPath, 'Test');
                if ($testBbox === false) {
                    $fontSizeNama = 150; // Minimal yang dijamin bekerja
                }
            }
            
            $bbox = imagettfbbox($fontSizeNama, 0, $scriptFontPath, $namaPeserta);
            if ($bbox !== false) {
                $textWidth = $bbox[4] - $bbox[0];
                $textX = ($width - $textWidth) / 2;
                // Warna hijau sesuai template
                $result = imagettftext($image, $fontSizeNama, 0, $textX, $namaY, $darkGreen, $scriptFontPath, $namaPeserta);
                
                Log::info('Certificate name written with TTF', [
                    'font_size' => $fontSizeNama,
                    'font_path' => $scriptFontPath,
                    'text_width' => $textWidth,
                    'result' => $result,
                    'bbox' => $bbox
                ]);
            } else {
                // Fallback ke built-in jika gagal
                $textWidth = imagefontwidth(5) * strlen($namaPeserta);
                $textX = ($width - $textWidth) / 2;
                imagestring($image, 5, $textX, $namaY - imagefontheight(5), $namaPeserta, $darkGreen);
                Log::error('Failed to write name with TTF font', [
                    'font_path' => $scriptFontPath,
                    'font_size' => $fontSizeNama
                ]);
            }
        } else {
            // Gunakan built-in font (kurang bagus, tapi tetap bisa)
            $textWidth = imagefontwidth(5) * strlen($namaPeserta);
            $textX = ($width - $textWidth) / 2;
            imagestring($image, 5, $textX, $namaY - imagefontheight(5), $namaPeserta, $darkGreen);
            
            Log::warning('Certificate using built-in font - font TTF not found', [
                'script_font_path' => $scriptFontPath,
                'available_fonts' => $scriptFontPaths
            ]);
        }

        // Tulis teks penghargaan (dengan word wrap jika terlalu panjang) - sesuai template
        $fontPenghargaan = $standardFontPath ?: $fontPath;
        $usePenghargaanTTF = $standardFontPath !== null || $fontPath !== null;
        
        if ($usePenghargaanTTF) {
            // Ukuran font untuk teks detail - HARUS SANGAT BESAR
            // Gunakan persentase dari tinggi yang lebih besar
            $fontSizePenghargaan = intval($height * 0.055); // 5.5% dari tinggi = SANGAT BESAR
            
            // Minimal 150px untuk teks detail (SANGAT BESAR - DIPERBESAR LAGI)
            if ($fontSizePenghargaan < 150) {
                $fontSizePenghargaan = 150; // Minimal 150px - SANGAT BESAR
            }
            
            // Maksimal 250px
            if ($fontSizePenghargaan > 250) {
                $fontSizePenghargaan = 250;
            }
            
            Log::info('Calculated font size for detail text', [
                'height' => $height,
                'calculated_percent' => intval($height * 0.055),
                'final_size' => $fontSizePenghargaan
            ]);
            
            // Test font
            $testBbox = @imagettfbbox($fontSizePenghargaan, 0, $fontPenghargaan, 'Test');
            if ($testBbox === false) {
                $fontSizePenghargaan = 60; // Fallback
            }
            
            $maxWidth = $width * 0.70; // Maksimal 70% lebar gambar (sesuai template)
            $lines = $this->wordWrap($teksPenghargaan, $fontPenghargaan, $fontSizePenghargaan, $maxWidth);
            
            // Jarak antar baris proporsional dengan ukuran font
            $lineHeight = intval($fontSizePenghargaan * 1.6); // 1.6x ukuran font untuk jarak lebih besar
            
            Log::info('Certificate detail text', [
                'font_size' => $fontSizePenghargaan,
                'font_path' => $fontPenghargaan,
                'lines_count' => count($lines)
            ]);
            $startY = $penghargaanY - (count($lines) - 1) * $lineHeight / 2;
            
            foreach ($lines as $index => $line) {
                $bbox = imagettfbbox($fontSizePenghargaan, 0, $fontPenghargaan, $line);
                $textWidth = $bbox[4] - $bbox[0];
                $textX = ($width - $textWidth) / 2;
                $y = $startY + ($index * $lineHeight);
                // Warna hitam sesuai template (bukan gray)
                imagettftext($image, $fontSizePenghargaan, 0, $textX, $y, $textBlack, $fontPenghargaan, $line);
            }
        } else {
            // Untuk built-in font, potong teks jika terlalu panjang
            $maxChars = 60;
            $wrappedText = wordwrap($teksPenghargaan, $maxChars, "\n", true);
            $lines = explode("\n", $wrappedText);
            $lineHeight = imagefontheight(3) + 5;
            $startY = $penghargaanY - (count($lines) - 1) * $lineHeight / 2;
            
            foreach ($lines as $index => $line) {
                $textWidth = imagefontwidth(3) * strlen($line);
                $textX = ($width - $textWidth) / 2;
                $y = $startY + ($index * $lineHeight);
                imagestring($image, 3, $textX, $y - imagefontheight(3), $line, $textBlack);
            }
        }

        // Gambar garis hijau horizontal (di bawah teks penghargaan)
        $garisStartX = $width * 0.20; // Mulai dari 20% lebar
        $garisEndX = $width * 0.80; // Berakhir di 80% lebar
        $garisThickness = 3; // Ketebalan garis
        imageline($image, $garisStartX, $garisY, $garisEndX, $garisY, $darkGreen);
        // Tambahkan ketebalan garis dengan menggambar beberapa garis
        for ($i = 1; $i < $garisThickness; $i++) {
            imageline($image, $garisStartX, $garisY + $i, $garisEndX, $garisY + $i, $darkGreen);
            imageline($image, $garisStartX, $garisY - $i, $garisEndX, $garisY - $i, $darkGreen);
        }

        // Tulis teks di bawah garis hijau (warna hijau dan diperbesar SANGAT BESAR)
        if ($useStandardTTF || $fontPath) {
            // Ukuran font untuk teks di bawah garis - HARUS SANGAT BESAR
            // Gunakan persentase dari tinggi yang lebih besar
            $fontSizeBawahGaris = intval($height * 0.07); // 7% dari tinggi = SANGAT BESAR
            
            // Minimal 180px untuk teks bawah (SANGAT BESAR - DIPERBESAR LAGI)
            if ($fontSizeBawahGaris < 180) {
                $fontSizeBawahGaris = 180; // Minimal 180px - SANGAT BESAR
            }
            
            // Maksimal 300px
            if ($fontSizeBawahGaris > 300) {
                $fontSizeBawahGaris = 300;
            }
            
            Log::info('Calculated font size for bottom text', [
                'height' => $height,
                'calculated_percent' => intval($height * 0.07),
                'final_size' => $fontSizeBawahGaris
            ]);
            
            // Test font
            $testBbox = @imagettfbbox($fontSizeBawahGaris, 0, $fontPenghargaan, 'Test');
            if ($testBbox === false) {
                $fontSizeBawahGaris = 80; // Fallback
            }
            
            $bbox = imagettfbbox($fontSizeBawahGaris, 0, $fontPenghargaan, $teksBawahGaris);
            if ($bbox !== false) {
                $textWidth = $bbox[4] - $bbox[0];
                $textX = ($width - $textWidth) / 2;
                imagettftext($image, $fontSizeBawahGaris, 0, $textX, $teksBawahY, $darkGreen, $fontPenghargaan, $teksBawahGaris);
            } else {
                // Fallback
                $textWidth = imagefontwidth(5) * strlen($teksBawahGaris);
                $textX = ($width - $textWidth) / 2;
                imagestring($image, 5, $textX, $teksBawahY - imagefontheight(5), $teksBawahGaris, $darkGreen);
            }
        } else {
            // Gunakan built-in font
            $textWidth = imagefontwidth(5) * strlen($teksBawahGaris);
            $textX = ($width - $textWidth) / 2;
            imagestring($image, 5, $textX, $teksBawahY - imagefontheight(5), $teksBawahGaris, $darkGreen);
        }

        // Simpan ke temporary file
        $tempPath = storage_path('app/temp/certificate_' . $user->user_id . '_' . $workshop->workshop_id . '_' . time() . '.png');
        
        // Pastikan directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Simpan gambar dengan kualitas tinggi
        imagepng($image, $tempPath, 9); // Kualitas 9 (0-9, 9 = terbaik)
        imagedestroy($image);

        return $tempPath;
    }

    /**
     * Helper function untuk word wrap teks dengan font TrueType
     */
    private function wordWrap($text, $fontPath, $fontSize, $maxWidth)
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = $currentLine === '' ? $word : $currentLine . ' ' . $word;
            $bbox = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $textWidth = $bbox[4] - $bbox[0];

            if ($textWidth > $maxWidth && $currentLine !== '') {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $testLine;
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }
}
