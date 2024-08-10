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
        Schema::create('berita_acara', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jadwal');
            $table->unsignedBigInteger('id_dosen');
            $table->date('tanggal');
            $table->string('pkk_bhsn');
            $table->string('spkk_bhsn');
            $table->string('media');
            $table->time('jam_ajar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara');
    }
};
