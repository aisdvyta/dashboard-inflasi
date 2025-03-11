<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function satker()
    {
        return $this->belongsTo(master_satker::class, 'id_satker');
    }

    public function role()
    {
        return $this->belongsTo(role::class, 'id_role');
    }
}
