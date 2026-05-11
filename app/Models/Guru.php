<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\BelongsToTenant;

class Guru extends Model
{
    use BelongsToTenant;
    protected $table = 'guru_manual';
    protected $fillable = ['ptk_id', 'nama', 'nuptk', 'nik', 'email', 'jenis_ptk', 'school_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->ptk_id)) {
                $model->ptk_id = (string) Str::uuid();
            }
        });
    }
}
