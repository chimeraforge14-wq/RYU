<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelengkapRapor extends Model
{
    use HasFactory;

    protected $table = 'pelengkap_rapor';

    protected $fillable = [
        'rombongan_belajar_id',
        'peserta_didik_id',
        'sakit',
        'izin',
        'tanpa_keterangan',
        'catatan_wali_kelas',
        'ekstrakurikuler_1',
        'keterangan_ekskul_1',
        'ekstrakurikuler_2',
        'keterangan_ekskul_2',
    ];
}
