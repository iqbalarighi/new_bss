<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pengecualian_absen', function (Blueprint $table) {
    $table->id();


    $table->unsignedBigInteger('karyawan_id');


    $table->integer('perusahaan')->unsigned();
    $table->integer('nama_kantor')->unsigned();

    $table->text('keterangan')->nullable();

    $table->boolean('is_active')->default(true);

    $table->date('tanggal_mulai')->nullable();
    $table->date('tanggal_selesai')->nullable();

    $table->timestamps();


    $table->foreign('karyawan_id')
        ->references('id')
        ->on('karyawan')
        ->onDelete('cascade');


    $table->unique(['karyawan_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengecualian_absen');
    }
};
