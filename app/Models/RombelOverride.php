<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class RombelOverride extends Model
{
    use BelongsToTenant;
    protected $fillable = ['rombongan_belajar_id', 'peserta_didik_id', 'action', 'from_rombongan_belajar_id', 'school_id'];
}
