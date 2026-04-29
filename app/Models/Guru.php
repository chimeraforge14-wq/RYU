<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guru extends Model
{
    protected $table = 'guru_manual';
    protected $guarded = [];

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
