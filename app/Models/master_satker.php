<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_satker extends Model
{
    /** @use HasFactory<\Database\Factories\MasterSatkerFactory> */
    use HasFactory;

    protected $fillable = ['nama_satker'];
}
