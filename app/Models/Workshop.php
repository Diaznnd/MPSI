<?php

// app/Models/Workshop.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    protected $table = 'workshops'; // Nama tabel
    protected $primaryKey = 'workshop_id'; // Primary key jika bukan 'id'

    // Kolom yang bisa diisi (Mass Assignment)
    protected $fillable = [
        'pemateri_id', 'judul', 'deskripsi', 'tanggal', 'waktu', 'lokasi', 'kuota', 'kuota_terisi', 'sampul_poster_url', 'status_workshop'
    ];

    public function pemateri()
    {
        return $this->belongsTo(User::class, 'pemateri_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, 'workshop_keyword', 'workshop_id', 'keyword_id', 'workshop_id', 'id');
    }

    // app/Models/Workshop.php
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'workshop_id', 'workshop_id');
    }

}
