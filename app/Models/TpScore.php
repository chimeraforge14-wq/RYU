<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpScore extends Model
{
    protected $fillable = ['tp_id', 'peserta_didik_id', 'score'];

    public function tp()
    {
        return $this->belongsTo(TpCpData::class, 'tp_id');
    }
}
