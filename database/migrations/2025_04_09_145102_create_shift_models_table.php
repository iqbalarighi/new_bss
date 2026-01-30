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
        Schema::create('shift', function (Blueprint $table) {
            $table->id();
            $table->string('shift');
            $table->unsignedBigInteger('kantor_id');
            $table->unsignedBigInteger('satker_id');
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->timestamps();

            $table->foreign('kantor_id')->references('id')->on('kantor')->onDelete('cascade');
            $table->foreign('satker_id')->references('id')->on('satker')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift');
    }
};
