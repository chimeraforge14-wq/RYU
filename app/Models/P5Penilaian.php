<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Penilaian extends Model
{
    protected $table = 'p5_penilaian';
    protected $fillable = [
        'proyek_id',
        'peserta_didik_id',
        'rombongan_belajar_id',
        'nilai',
        'catatan_proses'
    ];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'proyek_id');
    }
}
