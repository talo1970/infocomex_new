<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntidadProductoVendedor extends Model
{
    protected $fillable = [
        'entidad_id',
        'producto_id',
        'vendedor_id',
    ];

}
