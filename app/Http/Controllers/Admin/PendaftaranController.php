<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PendaftaranController extends Controller
{
    /**
     * Tampilkan daftar pendaftar berdasarkan workshop.
     */
    public function index($workshop_id)
    {
        // Ambil data workshop
        $workshop = Workshop::findOrFail($workshop_id);

        // Ambil semua pendaftar workshop beserta user-nya
        $pendaftar = Pendaftaran::with('user')
            ->where('workshop_id', $workshop_id)
            ->orderByDesc('tanggal_daftar')
            ->get();

        // Proses data peserta untuk template
        $participants = $pendaftar->map(function ($p) use ($workshop) {
            // Parse prodi_fakultas menjadi department dan faculty
            $prodiFakultas = $p->user->prodi_fakultas ?? '-';
            $parts = explode(' - ', $prodiFakultas);
            $department = $parts[0] ?? '-';
            $faculty = $parts[1] ?? ($parts[0] ?? '-');

            return [
                'name' => $p->user->nama ?? '-',
                'email' => $p->user->email ?? '-',
                'department' => $department,
                'faculty' => $faculty,
                'workshop_title' => $workshop->judul ?? '-',
                'registration_date' => $p->tanggal_daftar 
                    ? Carbon::parse($p->tanggal_daftar)->translatedFormat('d M Y H:i')
                    : '-',
            ];
        });

        // Hitung statistik
        $facultyCounts = [];
        $departmentCounts = [];

        foreach ($participants as $participant) {
            $faculty = $participant['faculty'];
            $department = $participant['department'];

            if ($faculty !== '-') {
                $facultyCounts[$faculty] = ($facultyCounts[$faculty] ?? 0) + 1;
            }
            if ($department !== '-') {
                $departmentCounts[$department] = ($departmentCounts[$department] ?? 0) + 1;
            }
        }

        $topFaculty = !empty($facultyCounts) 
            ? array_keys($facultyCounts, max($facultyCounts))[0] 
            : 'Tidak ada data';
        
        $topDepartment = !empty($departmentCounts) 
            ? array_keys($departmentCounts, max($departmentCounts))[0] 
            : 'Tidak ada data';

        // Kirim ke view
        return view('admin.workshop.pendaftar', compact('workshop', 'pendaftar', 'participants', 'topFaculty', 'topDepartment'));
    }
}
