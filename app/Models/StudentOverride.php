<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentOverride extends Model
{
    protected $fillable = ['peserta_didik_id', 'field_name', 'field_value'];
}
