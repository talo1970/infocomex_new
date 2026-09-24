<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class suscriptores extends Model
{
    protected $fillable = ['nombre', 'inicio', 'fin', 'dias', 'cantidad', 'importe_comision', 'completo'];

    /**
     * @return BelongsTo<Producto, $this>
     */
    public function minuta(): BelongsTo
    {
        return $this->belongsTo(Minuta::class);
    }
}
