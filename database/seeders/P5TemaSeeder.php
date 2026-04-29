<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\P5Tema;

class P5TemaSeeder extends Seeder
{
    public function run(): void
    {
        $temas = [
            'Gaya Hidup Berkelanjutan',
            'Kearifan Lokal',
            'Bhinneka Tunggal Ika',
            'Bangunlah Jiwa dan Raganya',
            'Suara Demokrasi',
            'Berekayasa dan Berteknologi untuk Membangun NKRI',
            'Kewirausahaan'
        ];

        foreach ($temas as $tema) {
            P5Tema::firstOrCreate(['nama' => $tema]);
        }
    }
}
