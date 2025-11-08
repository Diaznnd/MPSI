<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Table name
    protected $primaryKey = 'user_id'; // Define custom primary key
    protected $keyType = 'int'; // Specify key type, usually 'int' for auto-incremented primary keys
    public $timestamps = false; // Set to false if you're not using created_at and updated_at

    protected $fillable = [
        'nim_nidn', 'nama', 'email', 'password', 'role', 'prodi_fakultas', 'foto_profil_url', 'pemateri_until', 'nomor_telepon', 'alamat'
    ];

    public function workshops()
    {
        return $this->hasMany(Workshop::class, 'pemateri_id');
    }

    // Check if user has a specific role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Check if the user is a 'pemateri'
    public function isPemateri()
    {
        return $this->role === 'pemateri';
    }

    public function pendaftarans()
    {
        return $this->hasMany(Pendaftaran::class, 'user_id', 'user_id');
    }
}
