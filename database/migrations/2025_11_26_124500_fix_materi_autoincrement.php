<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * We modify the `materi_id` column to be AUTO_INCREMENT PRIMARY KEY
     * using a raw statement because changing to auto-increment via the
     * schema builder is not reliable across all DB drivers.
     */
    public function up()
    {
        // Only attempt for MySQL
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            // Be defensive: only modify if column exists and NOT AUTO_INCREMENT
            try {
                DB::statement("ALTER TABLE `materi_workshop` MODIFY `materi_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY");
            } catch (\Throwable $e) {
                // Log the error so artisan output shows it; do not fail silently
                echo "[migration] Could not modify materi_workshop.materi_id: " . $e->getMessage() . PHP_EOL;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            try {
                // Remove AUTO_INCREMENT, keep as INT NOT NULL.
                DB::statement("ALTER TABLE `materi_workshop` MODIFY `materi_id` INT NOT NULL");
            } catch (\Throwable $e) {
                echo "[migration] Could not revert materi_workshop.materi_id: " . $e->getMessage() . PHP_EOL;
            }
        }
    }
};
