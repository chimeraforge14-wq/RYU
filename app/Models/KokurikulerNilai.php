<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KokurikulerNilai extends Model
{
    protected $table = 'kokurikuler_nilai';
    protected $fillable = ['activity_id', 'peserta_didik_id', 'rombongan_belajar_id', 'nilai', 'catatan'];

    public function activity()
    {
        return $this->belongsTo(KokurikulerActivity::class, 'activity_id');
    }
}
