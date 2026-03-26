<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarjetaCliente extends Model
{
    protected $table = 'tarjeta_cliente';

    protected $fillable = [
        'user_id',
        'brand',
        'last4',
        'exp_month',
        'exp_year',
        'name'
    ];
}