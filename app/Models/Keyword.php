<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = ['keyword'];

    public $timestamps = false; // ⬅️ tambahkan ini

    public function workshops()
    {
        return $this->belongsToMany(Workshop::class, 'workshop_keyword', 'keyword_id', 'workshop_id', 'id', 'workshop_id');
    }
}
