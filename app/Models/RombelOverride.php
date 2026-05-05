<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RombelOverride extends Model
{
    protected $fillable = ['rombongan_belajar_id', 'peserta_didik_id', 'action', 'from_rombongan_belajar_id'];
}
