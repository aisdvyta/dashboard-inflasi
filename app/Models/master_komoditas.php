<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_komoditas extends Model
{
    use HasFactory;

    protected $table = 'master_komoditas';
    protected $primaryKey = 'kode_kom'; 
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['kode_kom', 'nama_kom', 'flag', 'flag_2'];

    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(master_komoditas::class, 'flag_2', 'flag');
    }
    public function children()
    {
        return $this->hasMany(master_komoditas::class, 'flag_2', 'flag');
    }
}
