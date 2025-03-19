<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class master_komoditas extends Model
{
    use HasFactory;

    protected $fillable = ['flag', 'nama_kom', 'kode_kom', 'kode_kom_path'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($komoditas) {
            if (empty($komoditas->kode_kom_path)) {
                $komoditas->kode_kom_path = self::generateKodeKomPath($komoditas);
            }
        });

        static::updating(function ($komoditas) {
            $komoditas->kode_kom_path = self::generateKodeKomPath($komoditas);
        });
    }

    private static function generateKodeKomPath($komoditas)
    {
        $previousPath = self::where('flag', '<', $komoditas->flag)
            ->orderBy('flag', 'desc')
            ->pluck('kode_kom_path')
            ->first();

        return $previousPath ? $previousPath . '.' . $komoditas->kode_kom : $komoditas->kode_kom;
    }
}
