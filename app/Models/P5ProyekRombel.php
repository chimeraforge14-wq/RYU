<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5ProyekRombel extends Model
{
    protected $table = 'p5_proyek_rombel';
    protected $fillable = ['proyek_id', 'rombongan_belajar_id'];

    public function proyek()
    {
        return $this->belongsTo(P5Proyek::class, 'proyek_id');
    }
}
