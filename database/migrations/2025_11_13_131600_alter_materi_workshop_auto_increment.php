<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Make materi_id AUTO_INCREMENT (MySQL)
        DB::statement('ALTER TABLE materi_workshop MODIFY materi_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        // Revert: remove AUTO_INCREMENT (best-effort)
        DB::statement('ALTER TABLE materi_workshop MODIFY materi_id BIGINT UNSIGNED NOT NULL');
    }
};
