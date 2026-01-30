<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tukar_jaga', function (Blueprint $table) {

            // pastikan kolom benar-benar ditambahkan dulu
            if (!Schema::hasColumn('tukar_jaga', 'perusahaan_id')) {
                $table->unsignedBigInteger('perusahaan_id')->after('user_id');
            }

            if (!Schema::hasColumn('tukar_jaga', 'kantor_id')) {
                $table->unsignedBigInteger('kantor_id')->after('perusahaan_id');
            }
        });

        Schema::table('tukar_jaga', function (Blueprint $table) {

            // baru tambahkan foreign key
            $table->foreign('perusahaan_id')
                ->references('id')
                ->on('perusahaan')
                ->onDelete('cascade');

            $table->foreign('kantor_id')
                ->references('id')
                ->on('kantor')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tukar_jaga', function (Blueprint $table) {
            $table->dropForeign(['perusahaan_id']);
            $table->dropForeign(['kantor_id']);

            $table->dropColumn('perusahaan_id');
            $table->dropColumn('kantor_id');
        });
    }

};
