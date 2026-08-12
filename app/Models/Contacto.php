<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contacto extends Model
{
    protected $fillable = [
        'entidad_id',
        'contacto',
        'mail',
        'telefono',
        'domicilio',
        'numero',
        'departamento_piso',
        'codigo_postal',
        'localidad',
        'provincia_id',
        'pais',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    public function entidad(): BelongsTo
    {
        return $this->belongsTo(Entidad::class, 'entidad_id');
    }
}
