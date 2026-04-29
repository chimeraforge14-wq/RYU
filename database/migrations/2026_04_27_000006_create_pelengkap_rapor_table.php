<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelengkap_rapor', function (Blueprint $table) {
            $table->id();
            $table->string('rombongan_belajar_id');
            $table->string('peserta_didik_id');
            $table->integer('sakit')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('tanpa_keterangan')->default(0);
            $table->text('catatan_wali_kelas')->nullable();
            $table->string('ekstrakurikuler_1')->nullable();
            $table->string('keterangan_ekskul_1')->nullable();
            $table->string('ekstrakurikuler_2')->nullable();
            $table->string('keterangan_ekskul_2')->nullable();
            $table->timestamps();

            $table->unique(['rombongan_belajar_id', 'peserta_didik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelengkap_rapor');
    }
};
