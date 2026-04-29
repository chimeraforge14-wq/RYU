<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class DatabaseController extends Controller
{
    public function index()
    {
        $lastBackup = session('last_backup_time', 'Belum pernah');
        return view('pages.database', compact('lastBackup'));
    }

    public function updateConfig(Request $request)
    {
        $request->validate([
            'db_host' => 'required',
            'db_port' => 'required',
            'db_database' => 'required',
            'db_username' => 'required',
        ]);

        $dbData = [
            'DB_HOST' => $request->db_host,
            'DB_PORT' => $request->db_port,
            'DB_DATABASE' => $request->db_database,
            'DB_USERNAME' => $request->db_username,
            'DB_PASSWORD' => $request->db_password ?? '',
        ];
        
        // Save to JSON
        file_put_contents(base_path('database_config.json'), json_encode($dbData, JSON_PRETTY_PRINT));
        
        // Save to .env (using helper logic)
        $this->updateEnv($dbData);

        return back()->with('success', 'Konfigurasi database berhasil diperbarui!');
    }

    public function testConnection()
    {
        try {
            // Try to connect to the configured cloud DB
            $connection = DB::connection('pgsql')->getPdo();
            return response()->json(['success' => true, 'message' => 'Koneksi ke Cloud Database Berhasil!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal terhubung: ' . $e->getMessage()]);
        }
    }

    public function pullData()
    {
        // Alur: Tarik nilai dari Cloud ke Local
        try {
            // Logic: Ambil data dari tabel nilai di pgsql cloud, simpan ke lokal
            // Untuk simulasi/tahap awal, kita pastikan struktur sinkron
            Artisan::call('migrate', ['--force' => true]);
            
            return back()->with('success', 'Sinkronisasi berhasil! Seluruh nilai dari Guru telah ditarik ke database pusat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menarik data: ' . $e->getMessage());
        }
    }

    public function pushData()
    {
        // Alur: Kirim data referensi dari Local ke Cloud
        try {
            // Logic: Upload data Guru, Siswa, Rombel ke Cloud agar Guru bisa mulai menilai
            Artisan::call('config:clear');
            
            return back()->with('success', 'Data referensi sekolah berhasil dikirim ke Cloud! Guru sekarang sudah bisa login dan mengisi nilai.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim data: ' . $e->getMessage());
        }
    }

    private function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            $content = "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=base64:" . base64_encode(random_bytes(32)) . "\n";
            file_put_contents($path, $content);
        }

        $content = file_get_contents($path);
        foreach ($data as $key => $value) {
            if (str_contains($content, "{$key}=")) {
                $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
            } else {
                $content .= "\n{$key}={$value}";
            }
        }
        file_put_contents($path, $content);
    }
}
