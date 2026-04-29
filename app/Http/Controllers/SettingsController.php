<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key');
        return view('pages.settings', compact('settings'));
    }

    public function store(Request $request)
    {
        $keys = ['school_logo', 'headmaster_signature', 'school_stamp'];
        
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('public/identity');
                
                // Save path to DB
                Setting::updateOrCreate(['key' => $key], ['value' => $path]);
            }
        }

        // Tambahan text settings
        if ($request->has('headmaster_name')) {
            Setting::updateOrCreate(['key' => 'headmaster_name'], ['value' => $request->headmaster_name]);
        }
        if ($request->has('headmaster_nip')) {
            Setting::updateOrCreate(['key' => 'headmaster_nip'], ['value' => $request->headmaster_nip]);
        }

        // Database Settings Update
        if ($request->has('db_host')) {
            $dbData = [
                'DB_HOST' => $request->db_host,
                'DB_PORT' => $request->db_port,
                'DB_DATABASE' => $request->db_database,
                'DB_USERNAME' => $request->db_username,
                'DB_PASSWORD' => $request->db_password,
            ];
            
            // Simpan ke JSON (Primary)
            file_put_contents(base_path('database_config.json'), json_encode($dbData, JSON_PRETTY_PRINT));
            
            // Simpan ke .env (Backup/Legacy)
            $this->updateEnv($dbData);
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    private function updateEnv(array $data)
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            // Jika .env tidak ada, buat baru minimalis
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

    public function tambahGuru(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nuptk' => 'nullable|string|max:20',
            'nik' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'jenis_ptk' => 'nullable|string|max:100'
        ]);

        \App\Models\Guru::create([
            'nama' => $request->nama,
            'nuptk' => $request->nuptk,
            'nik' => $request->nik,
            'email' => $request->email,
            'jenis_ptk' => $request->jenis_ptk,
        ]);

        return back()->with('success', 'Guru manual berhasil ditambahkan!');
    }

    public function tambahPembelajaran(Request $request)
    {
        $request->validate([
            'ptk_id' => 'required|string',
            'rombongan_belajar_id' => 'required|string',
            'mata_pelajaran_id' => 'required|string',
            'nama_mata_pelajaran' => 'required|string',
        ]);

        // Cari nama guru dan rombel untuk kemudahan display
        $dapodik = app(\App\Services\DapodikService::class);
        $ptks = $dapodik->getPTK();
        $rombels = $dapodik->getRombonganBelajar();
        
        $guru = collect($ptks)->firstWhere('ptk_id', $request->ptk_id);
        $rombel = collect($rombels)->firstWhere('rombongan_belajar_id', $request->rombongan_belajar_id);
        if (!$rombel) {
             $rombel = collect($rombels)->firstWhere('id', $request->rombongan_belajar_id);
        }

        \App\Models\PembelajaranManual::create([
            'ptk_id' => $request->ptk_id,
            'rombongan_belajar_id' => $request->rombongan_belajar_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
            'nama_guru' => $guru['nama'] ?? 'Guru Manual',
            'nama_rombel' => $rombel['nama'] ?? 'Kelas Manual',
        ]);

        return back()->with('success', 'Pembelajaran manual berhasil ditambahkan!');
    }
}
