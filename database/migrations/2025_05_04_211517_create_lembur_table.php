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
        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->integer('nip')->unsigned();
            $table->integer('perusahaan')->unsigned();
            $table->integer('kantor')->unsigned();
            $table->string('tgl_absen');
            $table->string('jam_in');
            $table->text('foto_in');
            $table->text('lokasi_in');
            $table->string('jam_out')->nullable();
            $table->text('foto_out')->nullable();
            $table->text('lokasi_out')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembur');
    }
};
