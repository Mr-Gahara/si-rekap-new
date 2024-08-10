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
        Schema::create('ket_mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_presensi');
            $table->boolean('status_confirm');
            $table->string('surat_bukti');
            $table->string('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ket_mahasiswa');
    }
};
