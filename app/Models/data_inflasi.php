<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class data_inflasi extends Model
{
    use HasFactory;
    protected $fillable = ['id_pengguna', 'nama', 'periode', 'jenis_data_inflasi', 'upload_at'];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
