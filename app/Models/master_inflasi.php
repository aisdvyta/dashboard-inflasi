<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_inflasi extends Model
{
    use HasFactory;
    protected $fillable = ['id_pengguna', 'nama', 'periode', 'jenis_data_inflasi', 'upload_at'];

    public $timestamps = false;

    public function pengguna()
    {
        return $this->belongsTo(user::class, 'id_pengguna');
    }
}
