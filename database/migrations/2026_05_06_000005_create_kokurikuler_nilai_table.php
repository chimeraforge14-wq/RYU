<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Penilaian Kokurikuler per Siswa per Aktivitas
        Schema::create('kokurikuler_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('kokurikuler_activities')->onDelete('cascade');
            $table->string('peserta_didik_id');
            $table->string('rombongan_belajar_id');
            $table->string('nilai')->nullable(); // BB / MB / BSH / SB
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'peserta_didik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kokurikuler_nilai');
    }
};
