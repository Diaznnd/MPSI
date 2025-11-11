<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah kolom status_workshop menjadi enum baru
        DB::statement("ALTER TABLE workshops MODIFY status_workshop ENUM('aktif', 'nonaktif', 'penuh', 'selesai') DEFAULT 'aktif'");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula
        DB::statement("ALTER TABLE workshops MODIFY status_workshop ENUM('aktif', 'nonaktif') DEFAULT 'aktif'");
    }
};