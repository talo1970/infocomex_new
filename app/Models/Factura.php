<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factura extends Model
{
    /**
     * @return HasMany<Minuta, $this>
     */
    public function factura_cliente(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function factura_banco(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

    /**
     * @return BelongsTo<Provincia, $this>
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

}
