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

class Entidad extends Model
{
    use HasUuids;
    use SoftDeletes;

    /*
        foreignUui en
            contacto,
            factura,
            recibo + banco_cheque,
            notas,
            movimiento_caja,
            minutas comprador_id, vendedor_id, entidad_cliente_id, bcra_id
            entidad_producto_vendedors
            entidad_honorario_productos
    */
    protected $fillable = [
        'tipo_entidad_id',
        'razon_social',
        'cuit',
        'porcentaje_comision',
        'telefono',
        'contacto',
        'mail',
        'dimicilio',
        'numero',
        'departamento_piso',
        'codigo_postal',
        'localidad',
        'provincia_id',
        'pais',
        'modo_factura',
        'codigo_proveedor',
        'observacion',
        'tipo_factura_id',
        'facturacion_limite',
        'facturacion_minima',
        'facturacion_fija',
        'tipo_operacion',
        'vendedor_id',
    ];

    protected function porcentajeComision(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /*
        Campos con importe
            porcentaje_comision,
            facturacion_limite,
            facturacion_minima,
            facturacion_fija,
     */

    protected function facturacionLimite(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value) => $value / 100,
            set: fn (float|int $value) => $value * 100,
        );
    }

    protected function facturacionMinima(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value) => $value / 100,
            set: fn (float|int $value) => $value * 100,
        );
    }

    protected function facturacionFija(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value) => $value / 100,
            set: fn (float|int $value) => $value * 100,
        );
    }

    /**
     * @return BelongsTo<TipoEntidad, $this>
     */
    public function tipo_entidad(): BelongsTo
    {
        return $this->belongsTo(TipoEntidad::class);
    }

    /**
     * @return BelongsTo<Provincia, $this>
     */
    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class);
    }

    /**
     * @return BelongsTo<TipoComprobante, $this>
     */
    public function tipo_factura(): BelongsTo
    {
        return $this->belongsTo(TipoComprobante::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<EntidadProductoVendedor, $this>
     */
    public function entidadProductoVendedor(): HasMany
    {
        return $this->hasMany(EntidadProductoVendedor::class);
    }

    /**
     * @return HasMany<Contacto, $this>
     */
    public function contactos(): HasMany
    {
        return $this->hasMany(Contacto::class);
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function compradores(): HasMany
    {
        return $this->hasMany(Minuta::class, 'comprador_id');
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function vendedores(): HasMany
    {
        return $this->hasMany(Minuta::class, 'vendedor_id');
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function entidad_cliente(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

    /**
     * @return HasMany<Minuta, $this>
     */
    public function bcra(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

    #[Scope]
    protected function bancos(Builder $query): void
    {
        $query->where('tipo_entidad_id', '2');
    }

    #[Scope]
    protected function clientes(Builder $query): void
    {
        $query->where('tipo_entidad_id', '1');
    }
}
