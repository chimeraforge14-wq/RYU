<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Tema extends Model
{
    protected $table = 'p5_tema';
    protected $fillable = ['nama'];

    public function proyek()
    {
        return $this->hasMany(P5Proyek::class, 'tema_id');
    }
}
