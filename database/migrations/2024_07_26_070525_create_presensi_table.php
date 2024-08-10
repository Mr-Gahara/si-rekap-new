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
        Schema::create('presensi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_presensi')->autoIncrement();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedInteger('id_tahun_ajar');
            $table->unsignedBigInteger('id_jadwal');
            $table->date('tanggal');
            $table->time('start_kls');
            $table->time('finish_kls')->nullable();
            $table->integer('kehadiran');
            $table->integer('ketidakhadiran');
            $table->enum('status', ['A','I','S'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
