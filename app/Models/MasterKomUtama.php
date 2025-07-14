<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKomUtama extends Model
{
    protected $table = 'master_kom_utama';
    protected $fillable = ['kode_kom', 'nama_kom'];
}
