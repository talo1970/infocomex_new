<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Valor extends Model
{
    protected $fillable = ['nombre'];

    /**
     * @return HasMany<Minuta, $this>
     */
    public function minutas(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }
}
