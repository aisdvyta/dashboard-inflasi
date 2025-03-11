<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_komoditas extends Model
{
    /** @use HasFactory<\Database\Factories\MasterKomoditasFactory> */
    use HasFactory;

    protected $fillable = ['nama_kom'];
}
