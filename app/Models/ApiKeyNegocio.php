<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKeyNegocio extends Model
{
    protected $table = 'api_keys_negocios';

    protected $fillable = [
        'negocio_externo_id',
        'nombre',
        'api_key_hash',
        'activa',
        'ultimo_uso',
    ];

    protected $hidden = [
        'api_key_hash',
    ];

    protected function casts(): array
    {
        return [
            'activa' => 'boolean',
            'ultimo_uso' => 'datetime',
        ];
    }
}
