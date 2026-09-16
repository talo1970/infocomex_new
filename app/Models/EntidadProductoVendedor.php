<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntidadProductoVendedor extends Model
{
    protected $table = 'entidad_producto_vendedors';

    protected $fillable = [
        'entidad_id',
        'producto_id',
        'vendedor_id',
    ];

    public function entidad(): BelongsTo
    {
        return $this->belongsTo(
            Entidad::class,
            'entidad_id',
            'id'
        );
    }

    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'producto_id',
            'id'
        );
    }

    public function vendedor()
    {
        return $this->belongsTo(
            User::class,
            'vendedor_id',
            'id'
        );
    }
}
