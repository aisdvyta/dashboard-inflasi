<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_inflasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_inflasi',
        'id_kom',
        'id_wil',
        'id_flag',
        'inflasi_MtM',
        'inflasi_YtD',
        'inflasi_YoY',
        'andil_MtM',
        'andil_YtD',
        'andil_YoY',
        'created_at'
    ];

    public $timestamps = false;

    public function inflasi()
    {
        return $this->belongsTo(master_inflasi::class, 'id_inflasi');
    }

    public function satker()
    {
        return $this->belongsTo(master_satker::class, 'id_wil', 'kode_satker');
    }

    public function komoditas()
    {
        return $this->belongsTo(master_komoditas::class, 'id_kom', 'kode_kom');
    }

    public function flag()
    {
        return $this->belongsTo(master_komoditas::class, 'id_flag', 'flag');
    }
}
