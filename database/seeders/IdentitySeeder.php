<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class IdentitySeeder extends Seeder
{
    public function run(): void
    {
        // Buat direktori jika belum ada
        $identityDir = storage_path('app/public/identity');
        if (!is_dir($identityDir)) {
            mkdir($identityDir, 0775, true);
        }

        // ── Generate logo placeholder (PNG) ──────────────────────────
        $logoPath = $identityDir . '/logo_sekolah.png';
        if (!file_exists($logoPath)) {
            $img = imagecreatetruecolor(120, 120);
            // Background biru dongker
            $bg   = imagecolorallocate($img, 10, 30, 80);
            $fill = imagecolorallocate($img, 30, 100, 220);
            $text = imagecolorallocate($img, 255, 255, 255);

            imagefilledrectangle($img, 0, 0, 120, 120, $bg);
            imagefilledellipse($img, 60, 60, 100, 100, $fill);
            // Tulis "SD" di tengah
            imagestring($img, 5, 45, 50, 'SD', $text);

            imagepng($img, $logoPath);
            imagedestroy($img);
        }

        // ── Generate TTD placeholder (PNG transparan) ─────────────────
        $sigPath = $identityDir . '/ttd_kepala.png';
        if (!file_exists($sigPath)) {
            $img = imagecreatetruecolor(200, 80);
            imagesavealpha($img, true);
            $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $transparent);
            $ink = imagecolorallocate($img, 30, 80, 200);
            // Tanda tangan sederhana (garis)
            imageline($img, 20, 40, 80, 25,  $ink);
            imageline($img, 80, 25, 140, 55, $ink);
            imageline($img, 140, 55, 180, 35, $ink);
            imageline($img, 20, 50, 60, 60,  $ink);

            imagepng($img, $sigPath);
            imagedestroy($img);
        }

        // ── Masukkan ke tabel settings ────────────────────────────────
        Setting::updateOrCreate(
            ['key' => 'school_logo'],
            ['value' => 'public/identity/logo_sekolah.png']
        );

        Setting::updateOrCreate(
            ['key' => 'headmaster_signature'],
            ['value' => 'public/identity/ttd_kepala.png']
        );

        $this->command->info('✅ Logo placeholder: public/identity/logo_sekolah.png');
        $this->command->info('✅ TTD placeholder  : public/identity/ttd_kepala.png');
        $this->command->info('');
        $this->command->info('Ganti dengan file PNG asli melalui menu Identitas Sekolah.');
    }
}
