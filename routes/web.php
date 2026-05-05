<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\NilaiController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/guru', [DashboardController::class, 'guru'])->name('guru');
    Route::get('/siswa', [DashboardController::class, 'siswa'])->name('siswa');
    
    // Nilai / Assessment Routes
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai');
    Route::get('/nilai/data', [NilaiController::class, 'getNilaiData'])->name('nilai.data');
    Route::get('/nilai/mapel', [NilaiController::class, 'getMapelByRombel'])->name('nilai.mapel');
    Route::post('/nilai/save', [NilaiController::class, 'save'])->name('nilai.save');

    // Pelengkap Rapor Routes
    Route::get('/pelengkap-rapor', [App\Http\Controllers\PelengkapRaporController::class, 'index'])->name('pelengkap_rapor');
    Route::post('/pelengkap-rapor', [App\Http\Controllers\PelengkapRaporController::class, 'store'])->name('pelengkap_rapor.store');

    // Sync Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/ambildapo', [SyncController::class, 'index'])->name('sync');
        Route::post('/ambildapo', [SyncController::class, 'processSync'])->name('sync.process');
    });

    // Settings / Identity Routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/pengaturan', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
        Route::post('/pengaturan', [App\Http\Controllers\SettingsController::class, 'store'])->name('settings.store');
        Route::post('/guru/tambah', [App\Http\Controllers\SettingsController::class, 'tambahGuru'])->name('guru.tambah');
        Route::post('/pembelajaran/tambah', [App\Http\Controllers\SettingsController::class, 'tambahPembelajaran'])->name('pembelajaran.tambah');
        
        // Kelola Database Routes (Admin Only)
        Route::get('/database/kelola', [App\Http\Controllers\DatabaseController::class, 'index'])->name('database.manage');
        Route::get('/database/test', [App\Http\Controllers\DatabaseController::class, 'testConnection'])->name('database.test');
        Route::post('/database/update', [App\Http\Controllers\DatabaseController::class, 'updateConfig'])->name('database.update');
        Route::post('/database/pull', [App\Http\Controllers\DatabaseController::class, 'pullData'])->name('database.pull');
    });

    // Database Actions (Guru & Admin)
    Route::post('/database/push', [App\Http\Controllers\DatabaseController::class, 'pushData'])->name('database.push');

    // UI Feature Pages
    Route::get('/profile', [App\Http\Controllers\PageController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [App\Http\Controllers\PageController::class, 'updateProfile'])->name('profile.update');
    // Admin Features
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/data-pengguna', [App\Http\Controllers\PageController::class, 'pengguna'])->name('pengguna');
        
        // Admin Log & Stats
        Route::get('/admin/log', [App\Http\Controllers\AdminLogController::class, 'index'])->name('admin.log');

        // Subject Management
        Route::get('/mata-pelajaran', [App\Http\Controllers\SubjectController::class, 'index'])->name('subjects.index');
        Route::post('/mata-pelajaran', [App\Http\Controllers\SubjectController::class, 'store'])->name('subjects.store');
        Route::put('/mata-pelajaran/{id}', [App\Http\Controllers\SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('/mata-pelajaran/{id}', [App\Http\Controllers\SubjectController::class, 'destroy'])->name('subjects.destroy');
        
        // P5 Group Management (Admin)
        Route::get('/kokurikuler/kelompok', [App\Http\Controllers\P5Controller::class, 'manageGroups'])->name('kokurikuler.groups');
        Route::post('/kokurikuler/kelompok', [App\Http\Controllers\P5Controller::class, 'storeGroup'])->name('kokurikuler.groups.store');
        Route::post('/kokurikuler/kegiatan', [App\Http\Controllers\P5Controller::class, 'storeActivity'])->name('kokurikuler.activities.store');
    });

    // Superadmin Features
    Route::middleware(['role:superadmin'])->group(function () {
        // Student Identity & Rombel Transfer
        Route::get('/peserta-didik', [App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
        Route::get('/peserta-didik/tambah', [App\Http\Controllers\StudentController::class, 'create'])->name('students.create');
        Route::post('/peserta-didik/tambah', [App\Http\Controllers\StudentController::class, 'store'])->name('students.store');
        Route::get('/peserta-didik/{id}/edit', [App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit');
        Route::post('/peserta-didik/{id}/update', [App\Http\Controllers\StudentController::class, 'update'])->name('students.update');
        Route::get('/peserta-didik/{id}/rombel', [App\Http\Controllers\StudentController::class, 'manageRombel'])->name('students.rombel');
        Route::post('/peserta-didik/{id}/rombel', [App\Http\Controllers\StudentController::class, 'updateRombel'])->name('students.rombel.update');

        // Advanced Settings
        Route::get('/pengaturan/super', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.super');
    });
    
    Route::get('/referensi/kelas/anggota/{id}', [App\Http\Controllers\PageController::class, 'anggotaRombel'])->name('anggota_rombel');
    Route::get('/referensi/{type}', [App\Http\Controllers\PageController::class, 'referensi'])->name('referensi');
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kokurikuler/perencanaan', [App\Http\Controllers\P5Controller::class, 'perencanaan'])->name('kokurikuler.perencanaan');
        Route::post('/kokurikuler/perencanaan', [App\Http\Controllers\P5Controller::class, 'storeProyek'])->name('kokurikuler.perencanaan.store');
        
        // P5 Group Management
        Route::get('/kokurikuler/kelompok', [App\Http\Controllers\P5Controller::class, 'manageGroups'])->name('kokurikuler.groups');
        Route::post('/kokurikuler/kelompok', [App\Http\Controllers\P5Controller::class, 'storeGroup'])->name('kokurikuler.groups.store');
        Route::post('/kokurikuler/kegiatan', [App\Http\Controllers\P5Controller::class, 'storeActivity'])->name('kokurikuler.activities.store');
    });
    Route::get('/kokurikuler/penilaian', [App\Http\Controllers\P5Controller::class, 'penilaian'])->name('kokurikuler.penilaian');
    Route::post('/kokurikuler/penilaian', [App\Http\Controllers\P5Controller::class, 'storePenilaian'])->name('kokurikuler.penilaian.store');
    Route::get('/status-penilaian/{type}', [App\Http\Controllers\PageController::class, 'statusPenilaian'])->name('status_penilaian');
    Route::get('/perkembangan/{type}', [App\Http\Controllers\PageController::class, 'perkembangan'])->name('perkembangan');
    
    // TP/CP Routes
    Route::get('/tp-cp', [App\Http\Controllers\TPController::class, 'index'])->name('tp.index');
    Route::post('/tp-cp', [App\Http\Controllers\TPController::class, 'store'])->name('tp.store');
    Route::get('/tp-scoring', [App\Http\Controllers\TPController::class, 'scoring'])->name('tp.scoring');
    Route::post('/tp-scoring', [App\Http\Controllers\TPController::class, 'storeScores'])->name('tp.scores.store');
    Route::get('/tp-export', [App\Http\Controllers\TPController::class, 'exportTemplate'])->name('tp.export');
    Route::post('/tp-import', [App\Http\Controllers\TPController::class, 'import'])->name('tp.import');

    Route::get('/cetak/{type}', [App\Http\Controllers\CetakController::class, 'index'])->name('cetak');
    Route::get('/print/leger', [App\Http\Controllers\CetakController::class, 'printLeger'])->name('cetak.print_leger');
    Route::get('/print/rapor/{rombel_id}/{peserta_didik_id}', [App\Http\Controllers\CetakController::class, 'printRapor'])->name('cetak.print_rapor');
    // Utility Routes (Accessible by both)
    Route::get('/backup-restore', [App\Http\Controllers\PageController::class, 'utility'])->name('backup');
    Route::get('/backup/export', [App\Http\Controllers\PageController::class, 'exportData'])->name('backup.export');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/kirim-dapodik', [App\Http\Controllers\PageController::class, 'kirimDapodik'])->name('kirim_dapodik');
        Route::post('/backup/import', [App\Http\Controllers\PageController::class, 'restoreData'])->name('backup.import');
    });
});

Route::get('/test-dapodik', function() {
    $token = request('token', 'CWecbcR0fSMzMi3');
    $npsn = request('npsn', '20236167');
    $semester = request('semester', '20251'); 
    $endpoint = request('endpoint', 'getPesertaDidik');
    
    $url = "http://localhost:5774/WebService/$endpoint?npsn=$npsn&semester_id=$semester";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $token"]);
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return response()->json([
        'http_code' => $httpcode,
        'url' => $url,
        'response' => json_decode($response) ?? $response
    ]);
});

Route::get('/debug-db', function() {
    try {
        \DB::connection()->getPdo();
        $tables = \DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
        return response()->json([
            'success' => true,
            'message' => 'Koneksi Database Berhasil!',
            'database' => config('database.connections.pgsql.database'),
            'tables' => $tables
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Koneksi Database Gagal: ' . $e->getMessage()
        ], 500);
    }
});

Route::get('/debug-student/{id}', function($id) {
    $path = storage_path('app/private/dapodik_data.json');
    if (!file_exists($path)) return response()->json(['error' => 'File not found at ' . $path]);
    $content = file_get_contents($path);
    $data = json_decode($content, true);
    $found = collect($data['pesertaDidik'] ?? [])->firstWhere('peserta_didik_id', $id);
    $rombel = [];
    foreach($data['rombonganBelajar'] ?? [] as $r) {
        if (isset($r['anggota_rombel'])) {
            $check = collect($r['anggota_rombel'])->firstWhere('peserta_didik_id', $id);
            if($check) $rombel[] = ['nama' => $r['nama'], 'data' => $check];
        }
    }
    return response()->json(['master' => $found, 'rombel' => $rombel]);
});
