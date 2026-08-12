<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{

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
