<?php

// app/Models/RequestWorkshop.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestWorkshop extends Model
{
    use HasFactory;

    protected $table = 'request_workshop'; // Nama tabel
    protected $primaryKey = 'request_id'; // Primary key jika bukan 'id'
    public $timestamps = false; // Jika tabel tidak memiliki kolom timestamp 'created_at' dan 'updated_at'

    // Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'status_request', 'tanggal_tanggapan', 'catatan_admin'
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
