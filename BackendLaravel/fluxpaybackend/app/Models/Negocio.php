<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $table = 'negocio';

    protected $fillable = [
        'nombre',
        'descripcion',
        'telefono',
        'status',
        'iduser'
    ];
    public function tickets()
{
    return $this->hasMany(Ticket::class, 'negocio_id');
}
}

