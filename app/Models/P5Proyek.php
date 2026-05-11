<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class P5Proyek extends Model
{
    use BelongsToTenant;
    protected $table = 'p5_proyek';
    protected $fillable = ['tema_id', 'nama_proyek', 'deskripsi', 'semester', 'school_id'];

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
