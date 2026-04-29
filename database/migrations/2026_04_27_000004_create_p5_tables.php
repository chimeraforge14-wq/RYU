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
        Schema::create('p5_tema', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('p5_proyek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tema_id')->constrained('p5_tema')->onDelete('cascade');
            $table->string('nama_proyek');
            $table->text('deskripsi')->nullable();
            $table->string('semester'); // e.g. 20251
            $table->timestamps();
        });

        Schema::create('p5_proyek_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('p5_proyek')->onDelete('cascade');
            $table->string('rombongan_belajar_id'); // ID dari Dapodik
            $table->timestamps();
        });

        Schema::create('p5_penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyek_id')->constrained('p5_proyek')->onDelete('cascade');
            $table->string('peserta_didik_id'); // ID dari Dapodik
            $table->string('rombongan_belajar_id'); // ID dari Dapodik
            $table->string('nilai', 5)->nullable(); // BB, MB, BSH, SB
            $table->text('catatan_proses')->nullable();
            $table->timestamps();

            $table->unique(['proyek_id', 'peserta_didik_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p5_penilaian');
        Schema::dropIfExists('p5_proyek_rombel');
        Schema::dropIfExists('p5_proyek');
        Schema::dropIfExists('p5_tema');
    }
};
