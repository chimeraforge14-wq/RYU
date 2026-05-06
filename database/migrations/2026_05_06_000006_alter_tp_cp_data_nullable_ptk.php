<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Buat ptk_id nullable agar superadmin (yang tidak punya ptk_id) bisa input TP/CP
        Schema::table('tp_cp_data', function (Blueprint $table) {
            $table->string('ptk_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tp_cp_data', function (Blueprint $table) {
            $table->string('ptk_id')->nullable(false)->change();
        });
    }
};
