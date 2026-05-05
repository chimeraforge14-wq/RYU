<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tujuan Pembelajaran & Capaian Pembelajaran
        Schema::create('tp_cp_data', function (Blueprint $table) {
            $table->id();
            $table->string('rombongan_belajar_id');
            $table->string('mata_pelajaran_id');
            $table->string('ptk_id');
            $table->enum('type', ['tp', 'cp']);
            $table->text('content');
            $table->string('kode')->nullable(); // e.g., TP1, TP2
            $table->timestamps();
        });

        // TP Scores per Student
        Schema::create('tp_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tp_id')->constrained('tp_cp_data')->onDelete('cascade');
            $table->string('peserta_didik_id');
            $table->integer('score')->default(0);
            $table->timestamps();
            $table->unique(['tp_id', 'peserta_didik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tp_scores');
        Schema::dropIfExists('tp_cp_data');
    }
};
