<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tukar_jaga', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->date('tanggal');

            // jam dihapus, pakai timestamp created_at
            $table->enum('shift', ['Pagi', 'Malam']);

            // lokasi gedung (nama kantor)
            $table->string('lokasi_gedung');

            $table->string('no_lap');

            $table->text('petugas_lama');
            $table->text('petugas_baru');

            $table->text('kejadian')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('karyawan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tukar_jaga');
    }
};
