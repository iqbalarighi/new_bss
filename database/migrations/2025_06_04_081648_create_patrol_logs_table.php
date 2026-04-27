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
        Schema::create('patrol_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('karyawan_id')->unsigned();
            $table->integer('perusahaan')->unsigned();
            $table->integer('kantor')->unsigned();
            $table->integer('checkpoint_id')->unsigned();
            $table->string('shift');
            $table->string('tgl_patrol');
            $table->timestamp('waktu_scan');
            $table->text('keterangan');
            $table->text('foto');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patrol_logs');
    }
};
