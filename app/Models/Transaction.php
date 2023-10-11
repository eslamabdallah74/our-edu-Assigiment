<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['paidAmount', 'currency', 'parentEmail', 'statusCode', 'paymentDate', 'parentIdentification'];
}
