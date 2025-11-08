<?php

// app/Models/Pendaftaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran'; // Nama tabel
    protected $primaryKey = 'pendaftaran_id'; // Primary key jika bukan 'id'
    public $timestamps = false; // Jika tabel tidak memiliki kolom timestamp 'created_at' dan 'updated_at'

    // Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'user_id', 'workshop_id', 'tanggal_daftar', 'status_pendaftaran'
    ];

    // app/Models/Pendaftaran.php

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi dengan workshop
    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshop_id');
    }

    /**
     * Boot method untuk menambahkan event listener
     */
    protected static function boot()
    {
        parent::boot();

        // Setelah pendaftaran dibuat, cek kuota dan nonaktifkan jika penuh
        static::created(function ($pendaftaran) {
            $workshop = $pendaftaran->workshop;
            if ($workshop) {
                $workshop->autoDeactivateIfQuotaFull();
            }
        });
    }

}

