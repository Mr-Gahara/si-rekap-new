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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedInteger('id_kelas');
            $table->unsignedInteger('id_matkul');
            $table->string('ruang');
            $table->string('hari');
            $table->time('start');
            $table->time('finish');
            $table->time('jumlah_jam');
            $table->string('token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
