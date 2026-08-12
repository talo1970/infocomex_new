<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clase extends Model
{
    protected $fillable = ['nombre'];

/*
DESCRIBE entidads;
DESCRIBE contactos;

#contactos
update contactos c
join entidads e
on c.entidad_id = e.id_old
set c.entidad_id = e.id;

#factura
update facturas f
join entidads e
on f.entidad_id = e.id_old
set f.entidad_id = e.id;

#recibo
update recibos r
join entidads e
on r.entidad_id = e.id_old
set r.entidad_id = e.id;
update recibos r
join entidads e
on r.banco_cheque = e.id_old
set r.banco_cheque = e.id;
#notas
update notas n
join entidads e
on n.entidad_id = e.id_old
set n.entidad_id = e.id;
#Movimiento_caja
update movimiento_cajas m
join entidads e
on m.entidad_id = e.id_old
set m.entidad_id = e.id;
#minutas comprador_id, vendedor_id, entidad_cliente_id, bcra_id
#comprador_id
update minutas mi
join entidads e
on mi.comprador_id = e.id_old
set mi.comprador_id = e.id;
#vendedor_id
update minutas mi
join entidads e
on mi.vendedor_id = e.id_old
set mi.vendedor_id = e.id;
#entidad_cliente_id
update minutas mi
join entidads e
on mi.entidad_cliente_id = e.id_old
set mi.entidad_cliente_id = e.id;
#bcra_id
update minutas mi
join entidads e
on mi.bcra_id = e.id_old
set mi.bcra_id = e.id;
#entidad_producto_vendedors
update entidad_producto_vendedors epv
join entidads e
on epv.entidad_id = e.id_old
set epv.entidad_id = e.id;
#entidad_honorario_productos
update entidad_honorario_productos ehp
join entidads e
on ehp.entidad_id = e.id_old
set ehp.entidad_id = e.id;
    */

    /**
     * @return HasMany<Minuta, $this>
     */
    public function minutas(): HasMany
    {
        return $this->hasMany(Minuta::class);
    }

}
