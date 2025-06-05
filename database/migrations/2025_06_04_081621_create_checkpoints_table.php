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
        Schema::create('checkpoints', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan')->unsigned();
            $table->integer('kantor')->unsigned();
            $table->integer('dept')->unsigned();
            $table->integer('satker')->unsigned();
            $table->string('nama');
            $table->string('lokasi'); // Bisa alamat atau koordinat
            $table->text('deskripsi'); // Bisa alamat atau koordinat
            $table->string('kode_unik')->unique();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkpoints');
    }
};
