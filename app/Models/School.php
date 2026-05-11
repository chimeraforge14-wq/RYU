<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'registration_code',
        'npsn',
        'name',
        'slug',
        'address',
        'dapodik_url',
        'dapodik_token',
        'active_semester_id'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
