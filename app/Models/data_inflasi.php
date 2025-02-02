<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_inflasi extends Model
{
    use HasFactory;
    protected $fillable = ['username', 'data_name', 'period', 'category', 'file_path'];
}
