<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Minuta extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $fillable = [
        'producto_id',
        'id_original',
        'numero',
        'periodo',
        'fecha',
        'estado_id',
        'comprador_id',
        'vendedor_id',
        'moneda_id',
        'clase_id',
        'valor_id',
        'importe',
        'tipo_cambio',
        'equivalente',
        'referencia_id',
        'tipo_documento_id',
        'observacion',
        'comision_comprador',
        'importe_comision_comprador',
        'comision_vendedor',
        'importe_comision_vendedor',
        'operacion',
        'factura_cliente_id',
        'factura_banco_id',
        'usuario_vendedor_id',
        'entidad_cliente_id',
        'bcra_id',
        'anio_desde',
        'anio_hasta',
        'anio_cantidad',
        'periodo_desde',
        'periodo_hasta',
        'importe_comision_unidad',
        'importe_comision_dolares',
        'importe_comision',
        'cantidad',
        'plazo',
        'fecha_vencimiento',
        'padre_id',
        'hijo_id',
    ];

    protected function equivalente(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    protected function importe(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    protected function tipoCambio(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 1000,
            set: fn ($value) => $value * 1000,
        );
    }

    protected function comisionVendedor(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    protected function importeComisionVendedor(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    protected function comisionComprador(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
    protected function importeComisionComprador(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * @return BelongsTo<Producto, $this>
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * @return BelongsTo<Estado, $this>
     */
    public function estado(): BelongsTo
    {
        return $this->belongsTo(Estado::class);
    }

    /**
     * @return BelongsTo<Entidad, $this>
     */
    public function comprador(): BelongsTo
    {
        return $this->belongsTo(Entidad::class, 'comprador_id', 'id');
    }

    /**
     * @return BelongsTo<Entidad, $this>
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(Entidad::class, 'vendedor_id');
    }

    /**
     * @return BelongsTo<Moneda, $this>
     */
    public function moneda(): BelongsTo
    {
        return $this->belongsTo(Moneda::class);
    }

    /**
     * @return BelongsTo<Clase, $this>
     */
    public function clase(): BelongsTo
    {
        return $this->belongsTo(Clase::class);
    }

    /**
     * @return BelongsTo<Valor, $this>
     */
    public function valor(): BelongsTo
    {
        return $this->belongsTo(Valor::class);
    }

    /**
     * @return BelongsTo<Referencia, $this>
     */
    public function Referencia(): BelongsTo
    {
        return $this->belongsTo(Referencia::class);
    }

    /**
     * @return BelongsTo<TipoDocumento, $this>
     */
    public function tipo_documento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    /**
     * @return BelongsTo<Factura, $this>
     */
    public function factura_cliente(): BelongsTo
    {
        return $this->belongsTo(Factura::class, 'factura_cliente_id');
    }

    /**
     * @return BelongsTo<Factura, $this>
     */
    public function factura_banco(): BelongsTo
    {
        return $this->belongsTo(Factura::class, 'factura_banco_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function usuario_vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_vendedor_id');
    }

    /**
     * @return BelongsTo<Entidad, $this>
     */
    public function entidad_cliente(): BelongsTo
    {
        return $this->belongsTo(Entidad::class, 'entidad_cliente_id')->orderBy('razon_social');
    }

    /**
     * @return BelongsTo<Entidad, $this>
     */
    public function bcra(): BelongsTo
    {
        return $this->belongsTo(Entidad::class, 'bcra_id');
    }

    /**
     * @return BelongsTo<Minuta, $this>
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(Minuta::class, 'padre_id');
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function hijos_padre(): HasMany
    {
        return $this->hasMany(Minuta::class, 'padre_id');
    }

    /**
     * @return BelongsTo<Minuta, $this>
     */
    public function hijo(): BelongsTo
    {
        return $this->belongsTo(Minuta::class, 'hijo_id');
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function padres_hijo(): HasMany
    {
        return $this->hasMany(Minuta::class, 'hijo_id');
    }

    #[Scope]
    protected function boletos(Builder $query): void
    {
        $query->where('producto_id', '1');
    }

    #[Scope]
    protected function com6401(Builder $query): void
    {
        $query->where('producto_id', '3');
    }

    #[Scope]
    protected function preciotransferencia(Builder $query): void
    {
        $query->where('producto_id', '9');
    }

    #[Scope]
    protected function maxboletocambio(Builder $query): void
    {
        $query->where('producto_id', '1')->whereYear('fecha', '=', date('Y') );
    }

    /*
        public function scopeBoletos(Builder $query): void
        {
            $query->where('producto_id', '=', '3');
        }
        */
}
