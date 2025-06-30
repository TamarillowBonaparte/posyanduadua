<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->enum('kader_status', ['utama', 'anggota'])->default('anggota')
                  ->comment('Status kader: kader utama atau anggota kader');
        });

        // Update existing admin users to have a default status
        DB::table('pengguna')
          ->where('role', 'admin')
          ->update(['kader_status' => 'anggota']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn('kader_status');
        });
    }
};
