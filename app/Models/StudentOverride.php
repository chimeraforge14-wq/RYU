<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class StudentOverride extends Model
{
    use BelongsToTenant;
    protected $fillable = ['peserta_didik_id', 'field_name', 'field_value', 'school_id'];
}
