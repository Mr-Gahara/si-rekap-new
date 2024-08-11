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
        Schema::create('sp', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sp');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_dosen_PA');
            $table->enum('jenis_sp', ['1', '2', '3']);
            $table->longText('lamp_sp');
            $table->longText('lamp_surat_pernyataan');
            $table->longText('lamp_pgl_ortu');
            $table->longText('lamp_ba_ortu');
            $table->enum('status', ['1', '2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sp');
    }
};
