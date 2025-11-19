<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\User;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        // Server-side search (opsional), tetap ada client-side filter di Blade
        $q = trim($request->get('q', ''));

        $query = Workshop::query();

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('judul', 'like', "%$q%")
                   ->orWhere('lokasi', 'like', "%$q%");
            });
        }

        $filterStatus = $request->get('status', 'all');

        if ($filterStatus === 'aktif') {
                $query->where(function($q) {
                    $q->where('status_workshop', 'aktif')
                    ->orWhereNull('status_workshop')
                    ->orWhere('status_workshop', '');
                });
            } elseif ($filterStatus === 'penuh') {
                $query->where('status_workshop', 'penuh');
            } elseif ($filterStatus === 'selesai') {
                $query->where('status_workshop', 'selesai');
            } elseif ($filterStatus === 'nonaktif') {
                $query->where('status_workshop', 'nonaktif');
            }

        $this->autoUpdateWorkshopStatus();

        // Urut terbaru & paginasi 12 per halaman
        $paginator = $query->orderByDesc('workshops.tanggal')
                   ->paginate(12)
                   ->withQueryString();

        // Normalisasi ke struktur yang Blade kamu pakai: ['id','title','date','time','location','image']
        $workshops = $paginator->getCollection()->map(function ($w) {
            $id   = $w->workshop_id ?? $w->id;
            $rawT = $w->tanggal ?? null;

            $date = $rawT ? \Carbon\Carbon::parse($rawT)->translatedFormat('d M Y') : '-';

            $jamMulai  = $w->waktu ?? null;
            $jamSelesai= $w->jam_selesai ?? null;
            $time = $jamMulai && $jamSelesai ? "{$jamMulai} – {$jamSelesai}" : ($jamMulai ?? '-');

            $title    = $w->judul ?? 'Workshop';
            $location = $w->lokasi ?? '—';
            $image    = $w->sampul_poster_url ? asset('storage/' . $w->sampul_poster_url) : null;

            return [
                'id'       => $id,
                'title'    => $title,
                'date'     => $date,
                'time'     => $time,
                'location' => $location,
                'image'    => $image,
            ];
        });


        // Replace collection di paginator supaya {{ $workshops->links() }} tetap bisa dipakai
        $paginator->setCollection($workshops);

        // Hitung statistik
        $stats = $this->calculateStatistics();

        return view('admin.workshop.index', [
            'workshops' => $paginator,
            'stats' => $stats,
            'filterStatus' => $filterStatus
        ]);
    }

    /**
     * Hitung statistik workshop
     */
    private function calculateStatistics()
    {
        // Total workshop
        $totalWorkshop = Workshop::count();
    
        // Workshop aktif
        $workshopAktif = Workshop::where('status_workshop', 'aktif')->count();
        
        // Workshop nonaktif
        $workshopNonaktif = Workshop::where('status_workshop', 'nonaktif')
            ->orWhereNull('status_workshop')
            ->count();

        // Hitung perubahan 7 hari terakhir (untuk total workshop)
        $last7Days = Workshop::where('tanggal', '>=', Carbon::now()->subDays(7))->count();
        $previous7Days = Workshop::whereBetween('tanggal', [
            Carbon::now()->subDays(14),
            Carbon::now()->subDays(7)
        ])->count();

        $percentageChange = 0;
        if ($previous7Days > 0) {
            $percentageChange = (($last7Days - $previous7Days) / $previous7Days) * 100;
        } elseif ($last7Days > 0) {
            $percentageChange = 100;
        }

        return [
            'total_workshop' => [
                'value' => $totalWorkshop,
                'change' => number_format($percentageChange, 1),
                'is_positive' => $percentageChange >= 0
            ],
            'workshop_aktif' => [
                'value' => $workshopAktif,
                'change' => '0.0', // Bisa ditambahkan logika perhitungan jika diperlukan
                'is_positive' => true
            ],
            'workshop_nonaktif' => [
                'value' => $workshopNonaktif,
                'change' => '0.0', // Bisa ditambahkan logika perhitungan jika diperlukan
                'is_positive' => false
            ]
        ];
    }

    public function create()
    {
        $pemateriUsers = User::where('role', 'pemateri')->get();
        return view('admin.workshop.create', compact('pemateriUsers'));
    }

    public function store(Request $request)
    {
        // Validasi input dari formulir
        $validated = $request->validate([
            'pemateri_id' => 'required|exists:users,user_id', // Pastikan pemateri_id valid
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => ['required', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/',],
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'kuota_terisi' => 'nullable|integer|min:0',
            'sampul_poster_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'status_workshop' => 'nullable|string|in:aktif,nonaktif,penuh,selesai', // Sesuaikan status
            'keywords' => 'nullable|array',  // Validasi untuk keywords
            'keywords.*' => 'nullable|string|max:255', // Validasi setiap keyword
        ], [
            'waktu.regex' => 'Format waktu harus HH:MM (contoh: 09:00 atau 15:30)',
            'tanggal.required' => 'Tanggal workshop wajib diisi.',
            'tanggal.date' => 'Tanggal workshop tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal workshop tidak boleh sebelum hari ini.',
        ]);

        $validated['status_workshop'] = $validated['status_workshop'] ?? 'aktif';

        // Cek apakah pemateri_id adalah user dengan role pemateri
        $pemateri = User::find($validated['pemateri_id']);
        if (!$pemateri->hasRole('pemateri')) {
            return redirect()->back()->withErrors(['pemateri_id' => 'Pemateri yang dipilih tidak valid.']);
        }

        // Proses upload sampul poster jika ada
        if ($request->hasFile('sampul_poster_url')) {
            $posterPath = $request->file('sampul_poster_url')->store('workshop_images', 'public');
            $validated['sampul_poster_url'] = $posterPath;
        }

        // Pastikan waktu tidak lewat dari saat ini jika tanggal = hari ini
        if (Carbon::parse($validated['tanggal'])->isToday()) {
            $inputTime = Carbon::createFromFormat('H:i', $validated['waktu']);
            if ($inputTime->lt(Carbon::now())) {
                return redirect()->back()
                    ->withErrors(['waktu' => 'Waktu tidak boleh lebih awal dari waktu sekarang.'])
                    ->withInput();
            }
        }

        // Simpan workshop ke database
        $workshop = Workshop::create($validated);

        // Menambahkan kata kunci (keywords) jika ada
        if ($request->has('keywords')) {
            foreach ($request->keywords as $kw) {
                $kw = trim($kw);
                if (!empty($kw)) { // ⬅️ pastikan tidak null/kosong
                    $keyword = \App\Models\Keyword::firstOrCreate(['keyword' => $kw]);
                    $workshop->keywords()->attach($keyword->id);
                }
            }
        }


        // Redirect kembali dengan pesan sukses
        return redirect()->route('admin.workshop.index')->with('success', 'Workshop berhasil dibuat.');
    }

    public function show(Workshop $workshop)
    {
        // Eager load relationships untuk menghindari N+1 query
        $workshop->load('pemateri', 'keywords');
        $this->autoUpdateWorkshopStatus();
        
        return view('admin.workshop.show', compact('workshop'));
    }

    public function edit(Workshop $workshop)
    {
        // Eager load relationships
        $workshop->load('pemateri', 'keywords');
        
        // Get all pemateri users for dropdown
        $pemateriUsers = User::where('role', 'pemateri')->get();
        
        return view('admin.workshop.edit', compact('workshop', 'pemateriUsers'));
    }

    public function update(Request $request, Workshop $workshop)
    {
        // Validasi input dari formulir
        $validated = $request->validate([
            'pemateri_id' => 'required|exists:users,user_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date|after_or_equal:today',
            'waktu' => ['required', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'kuota_terisi' => 'nullable|integer|min:0',
            'sampul_poster_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'status_workshop' => 'nullable|string|in:aktif,nonaktif,penuh,selesai',
            'keywords' => 'nullable|array',
            'keywords.*' => 'nullable|string|max:255',
            'remove_image' => 'nullable|boolean',
        ], [
            'waktu.regex' => 'Format waktu harus HH:MM (contoh: 09:00 atau 15:30)',
            'tanggal.required' => 'Tanggal workshop wajib diisi.',
            'tanggal.date' => 'Tanggal workshop tidak valid.',
            'tanggal.after_or_equal' => 'Tanggal workshop tidak boleh sebelum hari ini.',
        ]);

        // Cek apakah pemateri_id adalah user dengan role pemateri
        $pemateri = User::find($validated['pemateri_id']);
        if (!$pemateri->hasRole('pemateri')) {
            return redirect()->back()->withErrors(['pemateri_id' => 'Pemateri yang dipilih tidak valid.'])->withInput();
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Delete old image if exists
            if ($workshop->sampul_poster_url && Storage::disk('public')->exists($workshop->sampul_poster_url)) {
                Storage::disk('public')->delete($workshop->sampul_poster_url);
            }
            $validated['sampul_poster_url'] = null;
        }

        // Pastikan waktu tidak lewat dari saat ini jika tanggal = hari ini
        if (Carbon::parse($validated['tanggal'])->isToday()) {
            $inputTime = Carbon::createFromFormat('H:i', $validated['waktu']);
            if ($inputTime->lt(Carbon::now())) {
                return redirect()->back()
                    ->withErrors(['waktu' => 'Waktu tidak boleh lebih awal dari waktu sekarang.'])
                    ->withInput();
            }
        }

        // Proses upload sampul poster jika ada file baru
        if ($request->hasFile('sampul_poster_url')) {
            // Delete old image if exists
            if ($workshop->sampul_poster_url && Storage::disk('public')->exists($workshop->sampul_poster_url)) {
                Storage::disk('public')->delete($workshop->sampul_poster_url);
            }
            
            $posterPath = $request->file('sampul_poster_url')->store('workshop_images', 'public');
            $validated['sampul_poster_url'] = $posterPath;
        } else {
            // Keep existing image if no new file uploaded and not removed
            if (!isset($validated['sampul_poster_url'])) {
                unset($validated['sampul_poster_url']);
            }
        }

        // Update workshop
        $workshop->update($validated);

        // Update keywords - selalu detach semua dulu, lalu attach yang baru
        $workshop->keywords()->detach();
        
        // Attach keywords yang dikirim dari form
        if ($request->has('keywords') && is_array($request->keywords)) {
            foreach ($request->keywords as $kw) {
                $kw = trim($kw);
                if (!empty($kw)) {
                    $keyword = Keyword::firstOrCreate(['keyword' => $kw]);
                    $workshop->keywords()->attach($keyword->id);
                }
            }
        }

        // Cek kuota dan update status jika perlu
        $this->checkAndUpdateQuotaStatus($workshop);

        return redirect()->route('admin.workshop.show', $workshop->workshop_id)
            ->with('success', 'Workshop berhasil diperbarui.');
    }

    /**
     * Update otomatis status workshop:
     * - Jadi 'penuh' jika kuota sudah habis
     * - Jadi 'selesai' jika tanggal & waktu sudah lewat
     */
    private function autoUpdateWorkshopStatus()
    {
        $now = Carbon::now();

        $workshops = Workshop::whereIn('status_workshop', ['aktif', 'penuh'])->get();

        foreach ($workshops as $workshop) {
            $kuotaTerisi = $workshop->kuota_terisi ?? $workshop->pendaftaran()->count();
            $kuotaMax = $workshop->kuota;

            // 1️⃣ Jika kuota penuh
            if ($kuotaMax > 0 && $kuotaTerisi >= $kuotaMax && $workshop->status_workshop !== 'penuh') {
                $workshop->status_workshop = 'penuh';
                $workshop->save();
                continue; // lanjut ke workshop berikutnya
            }

            // 2️⃣ Jika sudah lewat tanggal & waktu
            $waktuWorkshop = Carbon::parse($workshop->tanggal . ' ' . $workshop->waktu);
            if ($waktuWorkshop->lt($now) && $workshop->status_workshop !== 'selesai') {
                $workshop->status_workshop = 'selesai';
                $workshop->save();
            }
        }
    }


    public function updateStatus(Request $request, Workshop $workshop)
    {
        $validated = $request->validate([
            'status_workshop' => 'required|in:aktif,nonaktif',
        ]);

        // Cek apakah kuota sudah penuh
        $currentTerisi = $workshop->kuota_terisi !== null ? $workshop->kuota_terisi : $workshop->pendaftaran()->count();
        $maxKuota = $workshop->kuota ?? 0;
        
        // Jika mencoba mengaktifkan workshop tapi kuota sudah penuh
        if ($validated['status_workshop'] === 'aktif' && $maxKuota > 0 && $currentTerisi >= $maxKuota) {
            return redirect()->route('admin.workshop.show', $workshop->workshop_id)
                ->with('error', 'Tidak dapat mengaktifkan workshop karena kuota sudah penuh (' . $currentTerisi . '/' . $maxKuota . ').');
        }

        $workshop->status_workshop = $validated['status_workshop'];
        $workshop->save();

        // Cek ulang setelah update - jika kuota penuh, otomatis nonaktifkan
        $this->checkAndUpdateQuotaStatus($workshop);

        $statusMessages = [
            'aktif' => 'Workshop telah diaktifkan',
            'nonaktif' => 'Workshop telah dinonaktifkan',
        ];

        return redirect()->route('admin.workshop.show', $workshop->workshop_id)
            ->with('success', $statusMessages[$validated['status_workshop']] ?? 'Status berhasil diperbarui.');
    }

    /**
     * Cek apakah kuota sudah penuh dan otomatis nonaktifkan jika penuh
     */
    private function checkAndUpdateQuotaStatus(Workshop $workshop)
    {
        $currentTerisi = $workshop->kuota_terisi ?? $workshop->pendaftaran()->count();
        $maxKuota = $workshop->kuota ?? 0;

        // Jika kuota penuh dan masih aktif, ubah ke "penuh"
        if ($maxKuota > 0 && $currentTerisi >= $maxKuota && $workshop->status_workshop === 'aktif') {
            $workshop->status_workshop = 'penuh';
            $workshop->save();
        }

        // Jika sudah lewat tanggal & waktu → selesai
        $now = Carbon::now();
        $tanggalWaktu = Carbon::parse($workshop->tanggal . ' ' . $workshop->waktu);
        if ($tanggalWaktu->lt($now) && $workshop->status_workshop !== 'selesai') {
            $workshop->status_workshop = 'selesai';
            $workshop->save();
        }
    }


    public function destroy(Workshop $workshop)
    {
        // Check if there are any pendaftaran records
        $pendaftaranCount = $workshop->pendaftaran()->count();
        
        if ($pendaftaranCount > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus workshop karena masih ada ' . $pendaftaranCount . ' peserta yang terdaftar. Silakan batalkan pendaftaran terlebih dahulu.');
        }

        // Delete associated image if exists
        if ($workshop->sampul_poster_url && Storage::disk('public')->exists($workshop->sampul_poster_url)) {
            Storage::disk('public')->delete($workshop->sampul_poster_url);
        }

        // Detach all keywords
        $workshop->keywords()->detach();

        // Delete the workshop
        $workshop->delete();

        return redirect()->route('admin.workshop.index')
            ->with('success', 'Workshop berhasil dibatalkan/dihapus.');
    }
}