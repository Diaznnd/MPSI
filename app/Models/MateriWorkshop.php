<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriWorkshop extends Model
{
    use HasFactory;

    protected $table = 'materi_workshop';
    protected $primaryKey = 'materi_id';
    public $timestamps = false;

    protected $fillable = [
        'workshop_id',
        'pemateri_id',
        'nama_file',
        'file_path',
        'tanggal_upload'
    ];

    /**
     * Relasi ke Workshop
     */
    public function workshop()
    {
        return $this->belongsTo(Workshop::class, 'workshop_id', 'workshop_id');
    }

    /**
     * Relasi ke User (Pemateri)
     */
    public function pemateri()
    {
        return $this->belongsTo(User::class, 'pemateri_id', 'user_id');
    }
}