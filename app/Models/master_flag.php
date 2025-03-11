<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_flag extends Model
{
    /** @use HasFactory<\Database\Factories\MasterFlagFactory> */
    use HasFactory;

    protected $fillable = ['id', 'desk_flag'];
}
