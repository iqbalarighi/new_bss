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
        Schema::create('satker', function (Blueprint $table) {
            $table->id();
            $table->integer('perusahaan')->unsigned();
            $table->integer('kantor')->unsigned();
            $table->integer('dept_id')->unsigned();
            $table->string('satuan_kerja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('satker');
    }
};
