<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KokurikulerGroup extends Model
{
    protected $fillable = ['name', 'coordinator_id', 'fase'];

    public function activities()
    {
        return $this->hasMany(KokurikulerActivity::class, 'group_id');
    }
}
