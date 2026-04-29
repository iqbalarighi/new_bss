<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePegawaiRollingTable extends Migration
{
    public function up()
    {
        Schema::create('pegawai_rolling', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');

            $table->unsignedBigInteger('perusahaan')->nullable();
            $table->unsignedBigInteger('kantor')->nullable();
            $table->unsignedBigInteger('dept')->nullable();
            $table->unsignedBigInteger('satker')->nullable();
            $table->unsignedBigInteger('jabatan')->nullable();
            $table->date('tanggal_efektif');
            $table->boolean('is_executed')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pegawai_rolling');
    }
}