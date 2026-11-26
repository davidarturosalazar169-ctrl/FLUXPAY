<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClienteNegocio extends Model
{
    protected $table = 'clientes_negocios';

    protected $fillable = [
        'nombreCliente',
        'idnegocio',
        'telefono',
        'puntos',
    ];

    protected $casts = [
        'puntos' => 'integer',
    ];
}