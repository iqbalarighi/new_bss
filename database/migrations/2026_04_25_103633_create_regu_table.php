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
        Schema::create('regu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_regu');
            $table->string('perusahaan');
            $table->unsignedBigInteger('danru_id')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->timestamps();
        });

        Schema::create('regu_anggota', function (Blueprint $table) {
            $table->id();
            // Pastikan tabel 'karyawan' sudah dibuat di file migrasi sebelumnya
            $table->foreignId('regu_id')->constrained('regu')->cascadeOnDelete();
            $table->foreignId('pegawai_id')->constrained('karyawan')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel anak (child) dulu, baru tabel induk (parent)
        Schema::dropIfExists('regu_anggota');
        Schema::dropIfExists('regu');
    }

};