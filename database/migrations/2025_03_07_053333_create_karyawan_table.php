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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->Integer('perusahaan')->unsigned();
            $table->Integer('nama_kantor')->unsigned();
            $table->Integer('dept')->unsigned();
            $table->Integer('satker')->unsigned();
            $table->Integer('jabatan')->unsigned();
            $table->string('nip', 30)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->date('tgl_lahir');
            $table->text('alamat');
            $table->text('domisili');
            $table->string('no_hp', 20)->nullable();
            $table->string('ko_drat', 20)->nullable();
            $table->string('bpjs_tk', 20)->nullable();
            $table->string('bpjs_sehat', 20)->nullable();
            $table->string('status', 20)->nullable();
            $table->text('foto')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};