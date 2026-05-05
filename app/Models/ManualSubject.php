<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualSubject extends Model
{
    protected $fillable = ['parent_id', 'mata_pelajaran_id', 'nama_mata_pelajaran', 'kelompok', 'urutan', 'is_active'];

    public function children()
    {
        return $this->hasMany(ManualSubject::class, 'parent_id', 'mata_pelajaran_id');
    }

    public function parent()
    {
        return $this->belongsTo(ManualSubject::class, 'parent_id', 'mata_pelajaran_id');
    }
}
