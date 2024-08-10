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
        Schema::create('kompen_mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kompen')->autoIncrement();
            $table->unsignedBigInteger('id_matkul');
            $table->unsignedBigInteger('id_tahun_ajar');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->integer('jumlah_kompen');
            $table->string('keterangan');
            $table->date('tgl_alpha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kompen_mahasiswa');
    }
};

