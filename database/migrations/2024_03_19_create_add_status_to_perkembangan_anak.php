<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('perkembangan_anak', function (Blueprint $table) {
            $table->string('status_berat_badan')->nullable()->after('berat_badan');
            $table->string('status_tinggi_badan')->nullable()->after('tinggi_badan');
        });
    }

    public function down()
    {
        Schema::table('perkembangan_anak', function (Blueprint $table) {
            $table->dropColumn('status_berat_badan');
            $table->dropColumn('status_tinggi_badan');
        });
    }
}; 