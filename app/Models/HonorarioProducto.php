<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HonorarioProducto extends Model
{
    protected $fillable = [
        'honorario_id',
        'producto_id',
        'importe',
    ];

    protected function importe(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value) => $value / 100,
            set: fn (float|int $value) => $value * 100,
        );
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function entidad_honorario_producto(): HasMany
    {
        return $this->hasMany(EntidadHonorarioProducto::class);
    }

}
