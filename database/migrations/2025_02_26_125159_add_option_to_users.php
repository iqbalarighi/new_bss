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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role')->default(2)->after('remember_token');
            $table->integer('perusahaan')->default(0)->after('remember_token');
            $table->integer('kantor')->default(0)->after('remember_token');
            $table->integer('dept')->default(0)->after('remember_token');
            $table->integer('satker')->default(0)->after('remember_token');
            $table->integer('jabatan')->default(0)->after('remember_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('perusahaan');
            $table->dropColumn('kantor');
            $table->dropColumn('dept');
            $table->dropColumn('jabatan');
            $table->dropColumn('satker');
        });
    }
};
