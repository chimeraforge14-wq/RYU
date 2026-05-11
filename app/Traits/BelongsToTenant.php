<?php

namespace App\Traits;

use App\Models\School;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public function initializeBelongsToTenant()
    {
        if (session()->has('registration_code') && config('database.connections.tenant.database')) {
            $this->setConnection('tenant');
        }
    }

    protected static function bootBelongsToTenant()
    {
        // Tetap simpan school_id untuk referensi internal jika diperlukan
        static::creating(function ($model) {
            if (session()->has('school_id') && !$model->school_id) {
                $model->school_id = session('school_id');
            }
        });

        // Global scope tidak lagi kritikal jika database sudah terpisah, 
        // tapi tetap biarkan sebagai layer keamanan tambahan.
        static::addGlobalScope('school', function (Builder $builder) {
            if (session()->has('school_id')) {
                $builder->where('school_id', session('school_id'));
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
