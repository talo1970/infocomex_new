<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotizacion extends Model
{
    protected $fillable = [
        'moneda_id',
        'fecha',
        'cotizacion',
    ];

    /*
     * Illuminate\Support\Number;
     * Number::format(123456789); -> 123.456.789
     * Number::toCurrency(10); -> $10.00
     * Number::format(1000.34, locale: 'es') -> 1.000,34
     * Number::toCurrency(25, currency: 'EUR'); -> €25.00
     * percentage
     * Number::toPercentage(25); -> 25%
     * Human Readable
     * Number::forHumans(1000); -> 1 thousand
     * Filesizes
     * Number::toFileSizes(1024); -> 1 KB
     * coming to laravel this week
     *
     */

    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    protected function cotizacion(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value) => $value / 1000,
            set: fn (float|int $value) => $value * 1000,
        );
    }
}
