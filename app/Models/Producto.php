<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    public function entidadesVendedores(): HasMany
    {
        return $this->hasMany(
            EntidadProductoVendedor::class,
            'producto_id', localKey: 'id'
        );
    }

    /**
     * @return HasMany<HonorarioProducto, $this>
     */
    public function honorario_producto(): HasMany
    {
        return $this->hasMany(HonorarioProducto::class);
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function minutas(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

    /**
     * @return HasMany<Contacto, $this>
     */
    public function facturacion(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
}
