<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected $fillable = ['balance', 'currency', 'email', 'id', 'created_at'];

    protected $casts = [
        'id' => 'string',
    ];


    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'parentEmail', 'email');
    }
    
}
