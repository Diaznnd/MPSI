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

        return view('admin.workshop.index', [
            'workshops' => $paginator
        ]);
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
            'tanggal' => 'required|date',
            'waktu' => ['required', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'kuota_terisi' => 'nullable|integer|min:0',
            'sampul_poster_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'status_workshop' => 'nullable|string|in:aktif,selesai,ditunda', // Sesuaikan status
            'keywords' => 'nullable|array',  // Validasi untuk keywords
            'keywords.*' => 'nullable|string|max:255', // Validasi setiap keyword
        ], [
            'waktu.regex' => 'Format waktu harus HH:MM (contoh: 09:00 atau 15:30)',
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
            'tanggal' => 'required|date',
            'waktu' => ['required', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'lokasi' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1',
            'kuota_terisi' => 'nullable|integer|min:0',
            'sampul_poster_url' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'status_workshop' => 'nullable|string|in:aktif,selesai,ditunda',
            'keywords' => 'nullable|array',
            'keywords.*' => 'nullable|string|max:255',
            'remove_image' => 'nullable|boolean',
        ], [
            'waktu.regex' => 'Format waktu harus HH:MM (contoh: 09:00 atau 15:30)',
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

        return redirect()->route('admin.workshop.show', $workshop->workshop_id)
            ->with('success', 'Workshop berhasil diperbarui.');
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
