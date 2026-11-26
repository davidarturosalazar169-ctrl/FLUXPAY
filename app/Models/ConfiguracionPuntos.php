<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionPuntos extends Model
{
    protected $table = 'configuracion_puntos';

    public $timestamps = false;

    protected $fillable = [
        'idnegocio',
        'puntos_por_compra',
        'puntos_para_canjear',
        'cantidad_de_pesos',
    ];
}
