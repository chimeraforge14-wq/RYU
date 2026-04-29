<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelajaran_manual', function (Blueprint $table) {
            $table->id();
            $table->uuid('rombongan_belajar_id');
            $table->string('mata_pelajaran_id');
            $table->uuid('ptk_id');
            $table->string('nama_mata_pelajaran');
            $table->string('nama_rombel')->nullable();
            $table->string('nama_guru')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelajaran_manual');
    }
};
