<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_wilayah extends Model
{
    /** @use HasFactory<\Database\Factories\MasterWilayahFactory> */
    use HasFactory;

    protected $fillable = ['nama_wilayah'];
}
