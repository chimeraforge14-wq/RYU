<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Proyek extends Model
{
    protected $table = 'p5_proyek';
    protected $fillable = ['tema_id', 'nama_proyek', 'deskripsi', 'semester'];

    public function tema()
    {
        return $this->belongsTo(P5Tema::class, 'tema_id');
    }

    public function rombel()
    {
        return $this->hasMany(P5ProyekRombel::class, 'proyek_id');
    }

    public function penilaian()
    {
        return $this->hasMany(P5Penilaian::class, 'proyek_id');
    }
}
