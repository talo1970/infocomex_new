<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Illuminate\Support\Number;


new #[Title('Edición de la Entidad')] class extends Component
{
    public \App\Models\Entidad $entidad;

    // 1 fila
    public $tipoentidadid;
    public $razon_social;
    public $cuit;
    // 2 fila
    public $tipofacturaid;
    public $comision;
    public $facturacion_limite;
    public $facturacion_minima;
    public $facturacion_fija;
    // 3 fila
    public $telefono;
    public $mail;
    // 4 fila
    public $contacto;
    public $domicilio;
    public $numero;
    public $codigoPostal;
    // 5 fila
    public $deptopiso;
    public $localidad;
    public $provinciaid;
    public $pais;
    // 6 fila
    public $modofacturacion;
    public $tipocliente;
    public $codigoproveedor;
    // 7 fila
    public $observacion;

    // producto por vendedor y honorarios por productos
    public $vendedorid;
    public $productoid;

    public $selectHonorariosid;

    public $productosSeleccionados = [];
    public $productos_inhabilitados = [];

    public $honorarios_producto = [];
    public $honorario_seleccionado;


    public function mount(\App\Models\Entidad $entidad ): void
    {
        $this->tipoentidadid = $entidad->tipo_entidad_id;
        $this->razon_social = $entidad->razon_social;
        $this->cuit = $entidad->cuit;
        // 2 fila
        $this->tipofacturaid = $entidad->tipo_factura_id;
        //$this->comision = $entidad->porcentaje_comision;
        $this->comision = Number::format($entidad->porcentaje_comision, locale: 'es');
        $this->facturacion_limite = Number::format($entidad->facturacion_limite, locale: 'es');
        $this->facturacion_minima = Number::format($entidad->facturacion_minima, locale: 'es');
        $this->facturacion_fija = Number::format($entidad->facturacion_fija, locale: 'es');

        // 3 fila
        $this->telefono = $entidad->telefono;
        $this->mail = $entidad->mail;
        // 4 fila
        $this->contacto = $entidad->contacto;
        $this->domicilio = $entidad->domicilio;
        $this->numero = $entidad->numero;
        $this->codigoPostal = $entidad->codito_postal;
        // 5 fila
        $this->deptopiso = $entidad->departamento_piso;
        $this->localidad = $entidad->localidad;
        $this->provinciaid = $entidad->provincia_id;
        $this->pais = $entidad->pais;
        // 6 fila
        $this->modofacturacion = $entidad->modo_factura;
        $this->tipocliente = $entidad->tipo_operacion;
        $this->codigoproveedor = $entidad->codigo_proveedor;
        // 7 Fila
        $this->observacion = $entidad->observacion;
    }

    #[Computed]
    public function tipoEntidades()
    {
        return \App\Models\TipoEntidad::select('id', 'nombre')->get();
    }

    #[Computed]
    public function tipoFacturas()
    {
        return \App\Models\TipoComprobante::select('id', 'nombre')->where('tipo', '=', 'factura')->get();
    }

    #[Computed]
    public function provincias()
    {
        return \App\Models\Provincia::select('id', 'nombre')->get();
    }

    #[Computed]
    public function vendedores()
    {
        return \App\Models\User::select('id', 'name')->get();
    }

    #[Computed]
    public function productos()
    {
        return \App\Models\Producto::select('id', 'nombre')->get();
    }
    #[Computed]
    public function productosHonorarios()
    {
        return \App\Models\Producto::select('id', 'nombre')
                                   ->where('honorario', '=', true)
                                   ->get();
    }

    #[Computed]
    public function honorarios()
    {

        return \App\Models\HonorarioProducto::where('producto_id', $this->productoid)
                                            ->get();
    }

    public function updatedVendedorid(): void
    {
        if ($this->vendedorid != null) {
            $this->productosSeleccionados = \App\Models\EntidadProductoVendedor::where('vendedor_id', $this->vendedorid)
                                                                                ->where('entidad_id', $this->entidad->id)
                                                                                ->pluck('producto_id')
                                                                                ->toArray();
            $this->productos_inhabilitados = \App\Models\EntidadProductoVendedor::where('vendedor_id', '!=', $this->vendedorid)
                                                                                ->where('entidad_id', $this->entidad->id)
                                                                                ->pluck('producto_id')
                                                                                ->toArray();
        } else {
            $this->productosSeleccionados = [];
        }
    }

    // graba el honorario seleccionado
    public function updatedHonorarioSeleccionado(): void
    {
        if ($this->honorario_seleccionado != null) {
                $hp = \App\Models\HonorarioProducto::where('producto_id', $this->productoid)
                                                    ->where('honorario_id', $this->honorario_seleccionado)->pluck('id')
                                                    ;
               $ehp = \App\Models\EntidadHonorarioProducto::create([
                                                                'entidad_id'  => $this->entidad->id,
                                                                'honorario_producto_id' => $hp[0]]);
        }
    }

    // para borrar honorarios que tiene seleccionado inmediatamente para a lo función  updatedHonorarioSeleccionado
    public function updatingHonorarioSeleccionado(): void
    {
       // para borrar el que está
        if ($this->honorario_seleccionado != null) {
            //if (count($this->honorario_seleccionado) > 0) {
                $ehp = \App\Models\EntidadHonorarioProducto::where('entidad_id', $this->entidad->id)
                                                           ->whereHas('honorario_producto', function($query) {
                                                               $query->where('producto_id', $this->productoid)
                                                                     ->where('honorario_id', $this->honorario_seleccionado);
                                                           });
                $ehp->delete();
            //}
        }
    }

    // cambio los prodúcto del vendedor
    public function updatedProductosSeleccionados(): void
    {
        if (count($this->productosSeleccionados) > 0 && $this->vendedorid |= null) {
            $entidadProductoVendedor = \App\Models\EntidadProductoVendedor::where('entidad_id', $this->entidad->id)
                                                                         ->where('vendedor_id', $this->vendedorid);
            //dd($entidaProductoVendedor->toSql());
            $entidadProductoVendedor->delete();
            foreach ($this->productosSeleccionados as $produc) {
                $epv = \App\Models\EntidadProductoVendedor::create([
                                                                       'entidad_id'  => $this->entidad->id,
                                                                       'producto_id' => $produc,
                                                                       'vendedor_id' => $this->vendedorid,
                                                                   ]);
            };
        }
    }

    public function updatedProductoid(): void
    {
        if ($this->productoid != null) {
            $this->honorario_seleccionado = \App\Models\HonorarioProducto::where('producto_id', $this->productoid)
                                                                                ->whereHas('entidad_honorario_producto', function($query) {
                                                                                    $query->where('entidad_id', $this->entidad->id);
                                                                                })->pluck('honorario_id')
                                                                                ->toArray();
        } else {
            $this->honorario_seleccionado = null;
        }
    }

    public function cancel(): void
    {
        $this->reset();
        $this->redirectRoute('entidades.index', navigate: true);
    }

    public function grabarEntidadEdit()
    {
        $this->comision = str_replace('.', '', $this->comision);
        $this->facturacion_limite = str_replace('.', '', $this->facturacion_limite);
        $this->facturacion_minima = str_replace('.', '', $this->facturacion_minima);
        $this->facturacion_fija = str_replace('.', '', $this->facturacion_fija);

        $this->validar();

        $this->comision = str_replace(',', '.', $this->comision);
        $this->facturacion_limite = str_replace(',', '.', $this->facturacion_limite);
        $this->facturacion_minima = str_replace(',', '.', $this->facturacion_minima);
        $this->facturacion_fija = str_replace(',', '.', $this->facturacion_fija);

        DB::transaction(function()  {
            $this->entidad->tipo_entidad_id = $this->tipoentidadid;
            $this->entidad->razon_social = $this->razon_social;
            $this->entidad->cuit = $this->cuit;
            $this->entidad->porcentaje_comision = $this->comision;
            $this->entidad->telefono = $this->telefono;
            $this->entidad->contacto = $this->contacto;
            $this->entidad->mail = $this->mail;
            $this->entidad->domicilio = $this->domicilio;
            $this->entidad->numero = $this->numero;
            $this->entidad->departamento_piso = $this->deptopiso;
            $this->entidad->codigo_postal = $this->codigoPostal;
            $this->entidad->localidad = $this->localidad;
            $this->entidad->provincia_id = $this->provinciaid;
            $this->entidad->pais = $this->pais;
            $this->entidad->modo_factura = $this->modofacturacion;
            $this->entidad->codigo_proveedor = $this->codigoproveedor;
            $this->entidad->observacion = $this->observacion;
            $this->entidad->tipo_factura_id = $this->tipofacturaid;
            $this->entidad->facturacion_limite = $this->facturacion_limite;
            $this->entidad->facturacion_minima = $this->facturacion_minima;
            $this->entidad->facturacion_fija = $this->facturacion_fija;

            $this->entidad->tipo_operacion = $this->tipocliente;

            if ($this->entidad->isDirty()) {
                Flux::toast(heading: 'Editar', text:'grabo por esta sucio', variant:'success', position:'top end');
                $this->entidad->save();
            } else {
                Flux::toast(heading: 'Editar', text:' no esta sucio Entidad grabada exitosamente', variant:'success', position:'top end');
            }
        });

        $this->dispatch('refreshComponent')->to('pages::entidades.index');

        $this->reset();
        $this->redirectRoute('entidades.index', navigate: true);
    }

    public function validar()
    {
        return $this->validate([
                        'tipoentidadid' => ['required', 'exists:tipo_entidads,id'],
                        'razon_social' => ['required','string','max:255'],
                        'cuit' => ['nullable','string','max:20'],
                        'tipofacturaid' => ['nullable', 'exists:tipo_comprobantes,id'],
                        'comision' => ['nullable', 'regex:/^\d+(,\d+)?$/'],
                        'facturacion_limite' => ['nullable', 'regex:/^\d+(,\d+)?$/'],
                        'facturacion_minima' => ['nullable', 'regex:/^\d+(,\d+)?$/'],
                        'facturacion_fija' => ['nullable', 'regex:/^\d+(,\d+)?$/'],
                        'telefono' => ['nullable','string','max:100'],
                        'mail' => ['nullable','string','max:255'],
                        'contacto' => ['nullable','string','max:100'],
                        'domicilio' => ['nullable','string','max:255'],
                        'numero' => ['nullable','string','max:25'],
                        'codigoPostal' => ['nullable','string','max:25'],
                        'deptopiso' => ['nullable','string','max:50'],
                        'localidad' => ['nullable','string','max:100'],
                        'provinciaid' => ['required', 'exists:provincias,id'],
                        'pais' => ['nullable','string','max:25'],
                        'modofacturacion' => ['nullable'],
                        'tipocliente' => ['nullable','string','max:25'],
                        'codigoproveedor' => ['nullable','string','max:25'],
                        'observacion' => ['nullable','string','max:255'],
                    ],
                    [
                        'tipoentidadid' => 'Se queriere el tipo de Entidad',
                        'razon_social' => 'Ingrese la Razón Social de la entidad',
                        'cuit' =>   'tiene que se menor a 20 caracteres',
                        'contacto' => 'tiene que se menor a 100 caracteres',
                        'provinciaid' => 'ingrese una provincia',
                        'comision' => 'Solo números',
                        'facturacion_limite' => 'Solo números',
                        'facturacion_minima' => 'Solo números',
                        'facturacion_fija' => 'Solo números',
                   ]);
    }
};
?>

<div>
    <div class="relative mb-4 w-full">
        <flux:badge color="indigo" inset="top bottom">
            <flux:heading size="xl" level="1">{{ __('Entidad') }}</flux:heading>
        </flux:badge>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Editar la Entidad') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
        <div class="text-center">
            <flux:badge color="indigo" >
                <flux:heading class="font-bold text-xl" size="lg">{{$this->razon_social}}</flux:heading>
            </flux:badge>
        </div>
        <flux:card>
            {{-- 1° fila --}}
            <div class="flex w-full flex-row items-start space-x-4 text-left">
                {{-- Razon Social --}}
                <div class="w-1/2">
                    <flux:input wire:model="razon_social" label="Razon Social" placeholder="Ingrese la razon social" />
                </div>
                {{-- CUIT --}}
                <div class="w-1/4">
                    <flux:input wire:model="cuit" label="CUIT" placeholder="Ingrese el CUIT" />
                </div>
                {{-- Tipo Entidad--}}
                <div class="w-1/4">
                    <flux:select wire:model.live="tipoentidadid" label="Tipo Entidad" placeholder="Seleccionar un Tipo">
                        @foreach($this->tipoEntidades as $tipo)
                            <flux:select.option value="{{$tipo->id}}" wire:key="{{$tipo->id}}">{{$tipo->nombre}}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
            {{-- 2° fila --}}
            <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                {{-- Tipo factura --}}
                <div class="w-1/5">
                    <flux:select wire:model="tipofacturaid" label="Tipo de Factura" placeholder="Seleccione el tipo de factura">
                        @foreach ($this->tipoFacturas as $tipoFactura)
                            <flux:select.option value="{{ $tipoFactura->id }}" wire:key="{{ $tipoFactura->id }}">{{ $tipoFactura->nombre }}</flux:select.option>
                        @endforeach
                    </flux:select>

                </div>
                {{-- Comisión --}}
                <div class="w-1/5">
                    <flux:input wire:model="comision" label="Comisión" placeholder="Ingrese la comisión" />
                </div>
                {{-- Limite --}}
                <div class="w-1/5">
                    <flux:input wire:model="facturacion_limite" label="Límite Máximo" placeholder="Ingrese el límite" />
                </div>
                {{-- Fact mínima --}}
                <div class="w-1/5">
                    <flux:input wire:model="facturacion_minima" label="Facturación mínima" placeholder="mínima" />
                </div>
                {{-- Fact Fija --}}
                <div class="w-1/5">
                    <flux:input wire:model="facturacion_fija" label="Facturación Fija" placeholder="Fija" />
                </div>
            </div>
            {{-- 3° fila --}}
            <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                {{-- Telefono --}}
                <div class="w-1/2">
                    <flux:input wire:model="telefono" label="Teléfono" placeholder="Ingrese Teléfono" />
                </div>
                {{-- e-Mail --}}
                <div class="w-1/2">
                    <flux:input wire:model="mail" label="e-Mail" placeholder="Ingrese el e-Mail" />
                </div>
            </div>
            {{-- 4° fila --}}
            <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                {{-- Contacto --}}
                <div class="w-1/3">
                    <flux:input wire:model="contacto" label="Contacto" placeholder="Ingrese el contacto" />
                </div>
                {{-- Dirección --}}
                <div class="w-1/2">
                    <flux:input wire:model="domicilio" label="Dirección" placeholder="Ingrese la Dirección" />
                </div>
                {{-- Nro --}}
                <div class="w-1/5">
                    <flux:input wire:model="numero" label="Número" placeholder="Ingrese el número" />
                </div>
                {{-- CP --}}
                <div class="w-1/5">
                    <flux:input wire:model="codigoPostal" label="Código Postal" placeholder="Ingrese el CP" />
                </div>
            </div>
            {{-- 5° fila --}}
            <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                {{-- piso --}}
                <div class="w-1/5">
                    <flux:input wire:model="deptopiso" label="Depto y/o piso" placeholder="Ingrese el Depto" />
                </div>
                {{-- Localidad --}}
                <div class="w-1/2">
                    <flux:input wire:model="localidad" label="Localidad" placeholder="Ingrese la Localidad" />
                </div>
                {{-- Provincia --}}
                <div class="w-1/4">
                    <flux:select wire:model="provinciaid" label="Provicia" placeholder="Seleccione una Provincia">
                        @foreach ($this->provincias as $provincia)
                            <flux:select.option value="{{ $provincia->id }}" wire:key="{{ $provincia->id }}">{{ $provincia->nombre }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                {{-- Pais--}}
                <div class="w-1/5">
                    <flux:input wire:model="pais" label="Pais" placeholder="Ingrese el Pais" />
                </div>
            </div>
            {{-- 6° fila --}}
            <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                {{-- Modo Facturación--}}
                <div class="w-1/4 mt-2">
                    <flux:radio.group wire:model="modofacturacion" label="Modo de Facturación" variant="pills">
                        <flux:radio value="Mensual" label="Mensual" />
                        <flux:radio value="Bimestral" label="Bimestral" />
                    </flux:radio.group>

                </div>
                {{-- Tipo Cliente importación o exportación --}}
                <div class="w-1/5">
                    <flux:select wire:model="tipocliente" label="Tipo de cliente" placeholder="Seleccione una opción">
                        <flux:select.option value="Exportación" wire:key="Exportación">Exportación</flux:select.option>
                        <flux:select.option value="Importación" wire:key="Importación">Importación</flux:select.option>
                    </flux:select>
                </div>
                {{-- Codigo Proveedor --}}
                <div class="w-1/5">
                    <flux:input wire:model="codigoproveedor" label="Código Proveedor" placeholder="Ingrese el Código" />
                </div>
            </div>
        </flux:card>
        {{-- Contactos--}}
        <flux:card class="mt-4">
                <div class="h-48 overflow-auto">
                    <livewire:pages::contactos.index :entidad="$entidad"/>
                </div>
        </flux:card>
        {{-- observación --}}
        <flux:card class="mt-4">
            {{-- Observación --}}
            <flux:textarea wire:model="observacion" label="Observación" placeholder="Ingrese la Observación" />
        </flux:card>
        <!-- Select de vendedor y productos honorarios -->
        <flux:card class="mt-4">
            {{-- 1° fila --}}
            <div class="flex w-full flex-row items-start space-x-4 text-left">
                {{-- vendedor --}}
                <div class="w-1/5">
                    <flux:select wire:model.live="vendedorid" label="Producto por Vendedor" placeholder="Seleccione un Vendedor">
                        <flux:select.option>-</flux:select.option>
                        @foreach ($this->vendedores as $vendedor)
                            <flux:select.option value="{{ $vendedor->id }}" wire:key="{{ $vendedor->id }}">{{ $vendedor->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                {{-- check productos --}}
                <div class="w-1/4">
                    <flux:checkbox.group wire:model.live="productosSeleccionados" label="Productos">
                        <ul>
                        @foreach ($this->productos as $producto)
                            <li class="my-1">
                            <flux:badge color="{{ $producto->id == in_array($producto->id, $this->productos_inhabilitados) ? 'red' : 'indigo' }}">
                                <flux:checkbox
                                    label="{{ $producto->nombre }}"
                                    value="{{ $producto->id }}"
                                    :disabled="$producto->id == in_array($producto->id, $this->productos_inhabilitados)"
                                />
                            </flux:badge>
                            </li>
                        @endforeach
                        </ul>
                    </flux:checkbox.group>
                </div>

                {{-- productos --}}
                <div class="w-1/4">
                    <flux:select wire:model.live="productoid" label="Producto por Vendedor" placeholder="Seleccione un Vendedor">
                        <flux:select.option>-</flux:select.option>
                        @foreach ($this->productosHonorarios as $producto)
                            <flux:select.option value="{{ $producto->id }}" wire:key="{{ $producto->id }}">{{ $producto->nombre }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                {{-- Honorarios --}}
                <div class="w-1/4">
                    <flux:radio.group wire:model.live="honorario_seleccionado" label="Seleccione un Honorario">
                       <ul>
                        @foreach ($this->honorarios as $honorario)
                           <li class="my-4">
                           <flux:radio
                                label="{{$honorario->importe}}"
                                value="{{ $honorario->honorario_id }}"
                            />
                           </li>
                        @endforeach
                       </ul>
                    </flux:radio.group>
                </div>
            </div>
        </flux:card>

        <div class="flex justify-end pt-4">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button wire:click="cancel()" variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button wire:click="grabarEntidadEdit()" type="submit" variant="primary" class="cursor-pointer ms-2">
                    Grabar Entidad
                </flux:button>
            </div>

            {{-- validaciones--}}
            @if($errors->any())
                <div class="aler aler-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

    <flux:toast position="top end" class="pt-24" />

</div>
