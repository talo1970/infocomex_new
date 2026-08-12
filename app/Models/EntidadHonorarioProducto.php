<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EntidadHonorarioProducto extends Model
{
    protected $fillable = [
        'entidad_id',
        'honorario_producto_id',
    ];

    public function honorario_producto(): BelongsTo
    {
        return $this->belongsTo(HonorarioProducto::class);
    }

}
