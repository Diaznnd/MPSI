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
        // USERS
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('user_id');
                $table->string('nim_nidn')->nullable();
                $table->string('nama')->nullable();
                $table->string('name')->nullable(); // for default UserFactory compatibility
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role', 20)->default('pengguna');
                $table->string('prodi_fakultas')->nullable();
                $table->string('foto_profil_url')->nullable();
                $table->dateTime('pemateri_until')->nullable();
                $table->string('nomor_telepon', 20)->nullable();
                $table->string('alamat', 255)->nullable();
                $table->rememberToken()->nullable();
                // No timestamps because App\Models\User::$timestamps = false
            });
        }

        // WORKSHOPS
        if (! Schema::hasTable('workshops')) {
            Schema::create('workshops', function (Blueprint $table) {
                $table->bigIncrements('workshop_id');
                $table->unsignedBigInteger('pemateri_id');
                $table->string('judul');
                $table->text('deskripsi');
                $table->date('tanggal')->nullable();
                $table->string('waktu', 5)->nullable();
                $table->string('jam_selesai', 5)->nullable();
                $table->string('lokasi')->nullable();
                $table->integer('kuota')->nullable();
                $table->integer('kuota_terisi')->nullable();
                $table->string('sampul_poster_url')->nullable();
                $table->string('status_workshop', 20)->nullable();
                $table->timestamps();

                $table->foreign('pemateri_id')
                    ->references('user_id')->on('users')
                    ->onDelete('cascade');
            });
        }

        // PENDAFTARAN
        if (! Schema::hasTable('pendaftaran')) {
            Schema::create('pendaftaran', function (Blueprint $table) {
                $table->bigIncrements('pendaftaran_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('workshop_id');
                $table->dateTime('tanggal_daftar')->nullable();
                $table->string('status_pendaftaran', 50)->nullable();

                $table->foreign('user_id')
                    ->references('user_id')->on('users')
                    ->onDelete('cascade');

                $table->foreign('workshop_id')
                    ->references('workshop_id')->on('workshops')
                    ->onDelete('cascade');
            });
        }

        // REQUEST WORKSHOP
        if (! Schema::hasTable('request_workshop')) {
            Schema::create('request_workshop', function (Blueprint $table) {
                $table->bigIncrements('request_id');
                $table->unsignedBigInteger('user_id');
                $table->string('judul');
                $table->text('deskripsi');
                $table->string('status_request', 50)->default('menunggu');
                $table->dateTime('tanggal_tanggapan')->nullable();
                $table->text('catatan_admin')->nullable();

                $table->foreign('user_id')
                    ->references('user_id')->on('users')
                    ->onDelete('cascade');
            });
        }

        // MATERI WORKSHOP
        if (! Schema::hasTable('materi_workshop')) {
            Schema::create('materi_workshop', function (Blueprint $table) {
                $table->increments('materi_id');
                $table->unsignedBigInteger('workshop_id');
                $table->string('judul_topik');
                $table->string('file_materi_url')->nullable();

                $table->foreign('workshop_id')
                    ->references('workshop_id')->on('workshops')
                    ->onDelete('cascade');
            });
        }

        // SERTIFIKAT
        if (! Schema::hasTable('sertifikat')) {
            Schema::create('sertifikat', function (Blueprint $table) {
                $table->bigIncrements('sertifikat_id');
                $table->unsignedBigInteger('pendaftaran_id');
                $table->string('file_url');
                $table->dateTime('tanggal_generate')->nullable();

                $table->foreign('pendaftaran_id')
                    ->references('pendaftaran_id')->on('pendaftaran')
                    ->onDelete('cascade');
            });
        }

        // KEYWORDS
        if (! Schema::hasTable('keywords')) {
            Schema::create('keywords', function (Blueprint $table) {
                $table->increments('id');
                $table->string('keyword')->unique();
                // No timestamps – matches App\Models\Keyword
            });
        }

        // PIVOT: WORKSHOP_KEYWORD
        if (! Schema::hasTable('workshop_keyword')) {
            Schema::create('workshop_keyword', function (Blueprint $table) {
                $table->unsignedBigInteger('workshop_id');
                $table->unsignedInteger('keyword_id');

                $table->primary(['workshop_id', 'keyword_id']);

                $table->foreign('workshop_id')
                    ->references('workshop_id')->on('workshops')
                    ->onDelete('cascade');

                $table->foreign('keyword_id')
                    ->references('id')->on('keywords')
                    ->onDelete('cascade');
            });
        }

        // ABSENSI
        if (! Schema::hasTable('absensi')) {
            Schema::create('absensi', function (Blueprint $table) {
                $table->id('absensi_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('workshop_id');
                $table->datetime('waktu_absensi');
                $table->string('status_absensi', 20)->default('hadir'); // hadir, tidak_hadir
                $table->timestamps();

                $table->foreign('user_id')
                    ->references('user_id')->on('users')
                    ->onDelete('cascade');

                $table->foreign('workshop_id')
                    ->references('workshop_id')->on('workshops')
                    ->onDelete('cascade');

                $table->unique(['user_id', 'workshop_id']);

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
        Schema::dropIfExists('workshop_keyword');
        Schema::dropIfExists('keywords');
        Schema::dropIfExists('sertifikat');
        Schema::dropIfExists('materi_workshop');
        Schema::dropIfExists('request_workshop');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('workshops');
        Schema::dropIfExists('users');
        Schema::dropIfExists('absensi');
    }
};
