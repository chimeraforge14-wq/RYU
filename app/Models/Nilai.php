<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class Nilai extends Model
{
    use BelongsToTenant;
    protected $table = 'nilai_sumatif';
    protected $fillable = [
        'rombongan_belajar_id',
        'mata_pelajaran_id',
        'peserta_didik_id',
        'nilai_tp1',
        'nilai_tp2',
        'nilai_sas',
        'nilai_akhir',
        'deskripsi_capaian',
        'school_id'
    ];
}
