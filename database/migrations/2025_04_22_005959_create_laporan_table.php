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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->Integer('user_id')->unsigned();
            $table->Integer('perusahaan')->unsigned();
            $table->Integer('kantor')->unsigned();
            $table->Integer('dept')->unsigned();
            $table->Integer('satker')->unsigned();
            $table->Integer('jabatan')->unsigned();
            $table->string('no_lap')->unique();
            $table->text('personil');
            $table->text('kegiatan');
            $table->text('keterangan')->nullable();
            $table->text('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
