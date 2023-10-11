<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $fillable = ['balance', 'currency', 'email', 'id', 'created_at'];

    protected $casts = [
        'id' => 'string',
    ];

    const authorized = 1;
    const decline = 1;
    const refunded = 1;


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'parentEmail', 'email');
    }
}
