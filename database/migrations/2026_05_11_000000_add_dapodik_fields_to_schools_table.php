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
        Schema::table('schools', function (Blueprint $table) {
            if (!Schema::hasColumn('schools', 'dapodik_url')) {
                $table->string('dapodik_url')->nullable();
            }
            if (!Schema::hasColumn('schools', 'dapodik_token')) {
                $table->string('dapodik_token')->nullable();
            }
            if (!Schema::hasColumn('schools', 'active_semester_id')) {
                $table->string('active_semester_id')->nullable();
            }
        });
    }
 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['dapodik_url', 'dapodik_token', 'active_semester_id']);
        });
    }
};
