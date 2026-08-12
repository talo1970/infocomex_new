<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;

new #[Title('Entidad Nueva')] class extends Component
{
    // 1 fila
    public $tipoentidadid = 1;
    public $razon_social;
    public $cuit;
    // 2 fila
    public $tipofacturaid;
    public $comision;
    public $limite;
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
    public $provinciaid = 25;
    public $pais;
    // 6 fila
    public $modofacturacion;
    public $tipocliente ='Exportación';
    public $codigoproveedor;
    // 7 fila
    public $observacion;

    public $entidad = null;

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

    public function cancel(): void
    {
        $this->reset();
        $this->redirectRoute('entidades.index', navigate: true);
    }

    public function grabarEntidad()
    {
        $this->validar();

        DB::transaction(function()  {

            $entidad = \App\Models\Entidad::create([
                                                'tipo_entidad_id' => $this->tipoentidadid,
                                                'razon_social' => $this->razon_social,
                                                'cuit' => $this->cuit,
                                                'tipo_factura_id' => $this->tipofacturaid,
                                                'porcentaje_comision' => $this->comision,
                                                'telefono' => $this->telefono,
                                                'contacto' => $this->contacto,
                                                'mail' => $this->mail,
                                                'domicilio' => $this->domicilio,
                                                'numero' => $this->numero,
                                                'departamento_piso' => $this->deptopiso,
                                                'codigo_postal' => $this->codigoPostal,
                                                'localidad' => $this->localidad,
                                                'provincia_id' => $this->provinciaid,
                                                'pais' => $this->pais,
                                                'modo_factura' => $this->modofacturacion,
                                                'codigo_proveedor' => $this->codigoproveedor,
                                                'observacion' => $this->observacion,
                                                'comision_limite' => $this->limite,
                                                'comision_minima' => $this->facturacion_minima,
                                                'comision_fija' => $this->facturacion_fija,
                                                'tipo_operacion' => $this->tipocliente,
                                                'vendedor_id' => 1,
                                           ]);
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
                                   'comision' => ['nullable'],
                                   'limite' => ['nullable'],
                                   'facturacion_minima' => ['nullable'],
                                   'facturacion_fija' => ['nullable'],
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
                               ]);
    }



};
?>

<div>
    <div class="relative mb-4 w-full">
        <flux:badge color="indigo" inset="top bottom">
            <flux:heading size="xl" level="1">{{ __('Entidad') }}</flux:heading>
        </flux:badge>
        <flux:subheading size="lg" class="mb-4 flex justify-between">{{ __('Alta de Entidad') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    <form wire:submit="grabarEntidad" class="space-y-4">
        <div class="text-center">
            <flux:badge color="indigo" >
                <flux:heading class="font-bold text-xl" size="lg">Nueva Entidad</flux:heading>
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
                    <flux:input wire:model="limite" label="Límite Máximo" placeholder="Ingrese el límite" />
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

        @if($this->entidad)
        {{-- Contactos--}}
        <flux:card class="mt-4">
            <div class="h-48 overflow-auto">
                <livewire:pages::contactos.index :entidad="$entidad"/>
            </div>
        </flux:card>
        @endif
        {{-- observación --}}
        <flux:card class="mt-4">
            {{-- Observación --}}
            <flux:textarea wire:model="observacion" label="Observación" placeholder="Ingrese la Observación" />
        </flux:card>
        @if($this->entidad)
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
        @endif

        <!-- Botones -->
        <div class="flex justify-end pt-4">
            <flux:spacer />
            <flux:modal.close>
                <flux:button wire:click="cancel()" variant="ghost" wireclass="cursor-pointer">Cancelar</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" class="cursor-pointer ms-2">
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
    </form>

    <flux:toast position="top end" class="pt-24" />
</div>
