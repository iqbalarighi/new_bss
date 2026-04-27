<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tukar_jaga_barang', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tukar_jaga_id');

            $table->string('nama_barang');
            $table->integer('jumlah')->default(1);
            $table->string('kondisi')->nullable();  // baik/rusak/hilang dll

            $table->timestamps();

            $table->foreign('tukar_jaga_id')
                ->references('id')
                ->on('tukar_jaga')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tukar_jaga_barang');
    }
};
