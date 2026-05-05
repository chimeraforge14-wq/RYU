<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kokurikuler Groups (P5)
        Schema::create('kokurikuler_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('coordinator_id')->nullable(); // PTK ID
            $table->string('fase')->nullable(); // A, B, C, etc.
            $table->timestamps();
        });

        // Kokurikuler Activities
        Schema::create('kokurikuler_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('kokurikuler_groups')->onDelete('cascade');
            $table->string('theme');
            $table->text('activity_name');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kokurikuler_activities');
        Schema::dropIfExists('kokurikuler_groups');
    }
};
