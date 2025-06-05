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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('perusahaan')->unsigned();
            $table->integer('kantor')->unsigned();
            $table->foreignId('checkpoint_id')->constrained()->onDelete('cascade');
            $table->string('shift');
            $table->text('keterangan');
            $table->text('foto');
            $table->timestamp('waktu_scan');
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
