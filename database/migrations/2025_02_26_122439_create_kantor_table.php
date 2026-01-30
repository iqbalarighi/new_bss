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
        Schema::create('kantor', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('perusahaan');
            $table->string('nama_kantor');
            $table->text('alamat');
            $table->text('radius');
            $table->text('lokasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kantor');
    }
};
