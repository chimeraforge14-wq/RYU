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
        Schema::create('nilai_sumatif', function (Blueprint $table) {
            $table->id();
            $table->string('rombongan_belajar_id'); // ID dari Dapodik
            $table->string('mata_pelajaran_id');
            $table->string('peserta_didik_id'); // ID dari Dapodik
            
            $table->integer('nilai_tp1')->nullable();
            $table->integer('nilai_tp2')->nullable();
            $table->integer('nilai_sas')->nullable();
            $table->integer('nilai_akhir')->nullable();
            
            $table->text('deskripsi_capaian')->nullable();
            $table->timestamps();

            // Memastikan satu siswa hanya punya satu row nilai per mapel di suatu kelas
            $table->unique(['rombongan_belajar_id', 'mata_pelajaran_id', 'peserta_didik_id'], 'nilai_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_sumatif');
    }
};
