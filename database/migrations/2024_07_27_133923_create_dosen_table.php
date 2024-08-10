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
        Schema::create('dosen', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->string('nidn')->unique();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->boolean('is_kaprodi');
            $table->string('no_hp');
            $table->string('foto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
