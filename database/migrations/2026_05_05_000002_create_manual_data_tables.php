<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Manual Subjects and Sub-subjects
        Schema::create('manual_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('parent_id')->nullable(); // For sub-subjects
            $table->string('mata_pelajaran_id')->unique(); // For manual ID or Dapodik ID
            $table->string('nama_mata_pelajaran');
            $table->string('kelompok')->nullable(); // Kelompok Rapor
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manual_subjects');
    }
};
