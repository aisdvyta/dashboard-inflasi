<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'penggunas';

    protected $primaryKey = 'id'; // Pastikan primary key benar
    protected $keyType = 'string'; // Karena UUID adalah string
    public $incrementing = false; // UUID bukan auto-increment

    public $timestamps = false;
    
    protected $casts = [
        'id' => 'string', // Pastikan ID diperlakukan sebagai string
    ];
    // public function getAuthIdentifierName()
    // {
    //     return 'nama';
    // }

    protected $fillable = [
        'id', 'nama', 'email', 'password', 'id_satker', 'id_role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function satker()
    {
        return $this->belongsTo(master_satker::class, 'id_satker', 'kode_satker');
    }

    public function role()
    {
        return $this->belongsTo(role::class, 'id_role');
    }

    public function masterInflasis()
    {
        return $this->hasMany(master_inflasi::class, 'id_pengguna');
    }

    public function getUsernameAttribute()
    {
        return $this->attributes['nama'];
    }
}
