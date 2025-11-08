<?php

// app/Models/Sertifikat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $table = 'sertifikat'; // Nama tabel
    protected $primaryKey = 'sertifikat_id'; // Primary key jika bukan 'id'
    public $timestamps = false; // Jika tabel tidak memiliki kolom timestamp 'created_at' dan 'updated_at'

    // Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'pendaftaran_id', 'file_url', 'tanggal_generate'
    ];

    // app/Models/Sertifikat.php

public function pendaftaran()
{
    return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'pendaftaran_id');
}

}
