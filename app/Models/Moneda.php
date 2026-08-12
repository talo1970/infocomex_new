<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Moneda extends Model
{
    protected $fillable = [
        'nombre',
        'simbolo',
        'pais',
    ];

    public function monedas(): HasMany
    {
        return $this->hasMany(Moneda::class);
    }
    /**
     * @return HasMany<Minuta, $this>
     */
    public function minutas(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

}
