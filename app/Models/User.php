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

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function getAuthIdentifierName()
    {
        return 'name';
    }

    public function satker()
    {
        return $this->belongsTo(master_satker::class, 'id_satker');
    }

    public function role()
    {
        return $this->belongsTo(role::class, 'id_role');
    }
}
