<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpCpData extends Model
{
    protected $table = 'tp_cp_data';
    protected $fillable = ['rombongan_belajar_id', 'mata_pelajaran_id', 'ptk_id', 'type', 'content', 'kode'];

    public function scores()
    {
        return $this->hasMany(TpScore::class, 'tp_id');
    }
}
