<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_manual', function (Blueprint $table) {
            $table->id();
            $table->uuid('ptk_id')->unique();
            $table->string('nama');
            $table->string('nuptk')->nullable();
            $table->string('nik')->nullable();
            $table->string('email')->nullable();
            $table->string('jenis_ptk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_manual');
    }
};
