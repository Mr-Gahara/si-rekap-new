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
        Schema::create('logs', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tahun_ajar')->autoIncrement();
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_dosen');
            $table->date('tahun_awal');
            $table->date('tahun_akhir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
