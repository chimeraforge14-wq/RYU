<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Overrides for Student Identity
        Schema::create('student_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('peserta_didik_id')->index(); // ID from Dapodik
            $table->string('field_name');
            $table->text('field_value')->nullable();
            $table->timestamps();
            $table->unique(['peserta_didik_id', 'field_name']);
        });

        // Overrides for School Profile
        Schema::create('school_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('field_name')->unique();
            $table->text('field_value')->nullable();
            $table->timestamps();
        });

        // Overrides for Rombongan Belajar (Member Transfers/Manual Adds)
        Schema::create('rombel_overrides', function (Blueprint $table) {
            $table->id();
            $table->string('rombongan_belajar_id')->index();
            $table->string('peserta_didik_id')->index();
            $table->enum('action', ['add', 'remove', 'transfer'])->default('add');
            $table->string('from_rombongan_belajar_id')->nullable();
            $table->timestamps();
            $table->unique(['rombongan_belajar_id', 'peserta_didik_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_overrides');
        Schema::dropIfExists('school_overrides');
        Schema::dropIfExists('rombel_overrides');
    }
};
