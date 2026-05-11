<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'registration_code')) {
                $table->string('registration_code')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('schools', 'npsn')) {
                $table->string('npsn')->unique()->nullable()->after('registration_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['registration_code', 'npsn']);
        });
    }
};
