<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // In the main app this migration will run after users & workshops tables exist.
        // For the sqlite in-memory testing database, core tables may be created by a
        // later, testing-only migration. To avoid foreign key errors there, we guard
        // this creation by checking that the referenced tables already exist.
        if (Schema::hasTable('users') && Schema::hasTable('workshops') && ! Schema::hasTable('absensi')) {
            Schema::create('absensi', function (Blueprint $table) {
                $table->id('absensi_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('workshop_id');
                $table->datetime('waktu_absensi');
                $table->string('status_absensi', 20)->default('hadir'); // hadir, tidak_hadir
                $table->timestamps();
                
                // Foreign keys
                $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
                $table->foreign('workshop_id')->references('workshop_id')->on('workshops')->onDelete('cascade');
                
                // Unique constraint: user can only have one attendance per workshop
                $table->unique(['user_id', 'workshop_id']);
                
                // Indexes for better performance
                $table->index('workshop_id');
                $table->index('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
