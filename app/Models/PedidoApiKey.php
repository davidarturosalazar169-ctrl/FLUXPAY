<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoApiKey extends Model
{
    protected $table = 'pedidos_apikey';

    protected $fillable = [
        'api_key_negocio_id',
        'negocio_externo_id',
        'referencia_externa',
        'monto',
        'stripe_payment_intent',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
        ];
    }
}
