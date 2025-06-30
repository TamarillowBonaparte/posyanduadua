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
        Schema::table('stunting', function (Blueprint $table) {
            // Ubah enum untuk menambahkan 'Resiko Stunting'
            DB::statement("ALTER TABLE stunting MODIFY status ENUM('Stunting', 'Resiko Stunting', 'Tidak Stunting') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stunting', function (Blueprint $table) {
            // Kembalikan enum ke nilai semula
            DB::statement("ALTER TABLE stunting MODIFY status ENUM('Stunting', 'Tidak Stunting') NOT NULL");
        });
    }
};
