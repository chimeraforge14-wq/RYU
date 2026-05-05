<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KokurikulerActivity extends Model
{
    protected $fillable = ['group_id', 'theme', 'activity_name', 'description'];

    public function group()
    {
        return $this->belongsTo(KokurikulerGroup::class, 'group_id');
    }
}
