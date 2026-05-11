<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('schools')) {
            Schema::create('schools', function (Blueprint $table) {
                $table->id();
                $table->string('registration_code')->unique();
                $table->string('npsn')->unique();
                $table->string('name');
                $table->string('address')->nullable();
                $table->string('dapodik_url')->nullable();
                $table->string('dapodik_token')->nullable();
                $table->string('active_semester_id')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('schools', function (Blueprint $table) {
                if (!Schema::hasColumn('schools', 'registration_code')) {
                    $table->string('registration_code')->unique()->nullable();
                }
                if (!Schema::hasColumn('schools', 'npsn')) {
                    $table->string('npsn')->unique()->nullable();
                }
                if (!Schema::hasColumn('schools', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('schools', 'address')) {
                    $table->string('address')->nullable();
                }
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

        // Add school_id to existing tables
        $tables = [
            'nilai_sumatif',
            'p5_proyek',
            'p5_penilaian',
            'pelengkap_rapor',
            'settings',
            'guru_manual',
            'pembelajaran_manual',
            'rombel_overrides',
            'school_overrides',
            'student_overrides',
            'tp_cp_data',
            'tp_scores',
            'kokurikuler_groups',
            'kokurikuler_activities',
            'kokurikuler_nilai',
            'manual_subjects'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'school_id')) {
                        $table->unsignedBigInteger('school_id')->nullable()->after('id');
                        
                        // If table has a unique key that should now be scoped to school
                        if ($tableName === 'settings') {
                            try {
                                $table->dropUnique('settings_key_unique');
                            } catch (\Exception $e) {
                                // Ignore if index doesn't exist
                            }
                            $table->unique(['school_id', 'key']);
                        }
                        
                        $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
