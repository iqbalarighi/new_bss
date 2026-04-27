<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->boolean('face_verified')
                ->default(false)
                ->after('lokasi_out');

            $table->float('face_score', 5, 4)
                ->nullable()
                ->after('face_verified');

            $table->boolean('is_fallback')
                ->default(false)
                ->after('face_score');

            $table->timestamp('verified_at')
                ->nullable()
                ->after('is_fallback');

            $table->string('face_status')
            ->nullable()
            ->after('verified_at');
            // success | failed | fallback
        });
    }

    public function down(): void
    {
        Schema::table('absen', function (Blueprint $table) {
            $table->dropColumn([
                'face_verified',
                'face_score',
                'is_fallback',
                'verified_at',
                'face_status'
            ]);
        });
    }
};

