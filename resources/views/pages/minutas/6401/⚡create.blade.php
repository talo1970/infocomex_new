<?php

    use Livewire\Attributes\Computed;
    use Livewire\Component;

    new class extends Component {

        public $maximo;
        public $fechaboleto;
        public $estadoid;

        public $entidadid;
        public $productoId = 3;
        public $entidad_cliente_id;

        public $numeroboleto;
        public $bancoid;
        public $periododmes;
        public $periododanio;
        public $periodohmes;
        public $periodohanio;
        public $observacion;
        public $comisiondolares;
        public $totalcomisiondolares;
        public $tipocambio;
        public $totalminuta;
        public $nombre_vendedor;
        public $vendedorid;

        private function esVendedor(): bool
        {
            return auth()->user()->hasRole('vendedor');
        }
/*
        private function cargarProductos()
        {
            if ($this->esVendedor()) {

                $this->productos = Producto::query()
                                           ->whereHas('entidadesVendedores', function ($query) {
                                               $query->where('vendedor_id', auth()->id());
                                           })
                                           ->orderBy('nombre')
                                           ->get();

            } else {

                $this->productos = Producto::query()
                                           ->orderBy('nombre')
                                           ->get();
            }
        }
*/

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::select('id', 'nombre')->get();
        }

        #[Computed]
        public function clientes()
        {
            //return \App\Models\Entidad::clientes()->select('id', 'razon_social')->get();
            $productoId = 3;
            if ($this->esVendedor()) {
                $this->clientes = \App\Models\Entidad::query()
                                         ->whereHas('productosVendedores', function ($query) {
                                             $query
                                                 ->where('producto_id', '3')
                                                 ->where('vendedor_id', auth()->id());
                                         })
                                         ->orderBy('razon_social')
                                         ->get(); //toSql();
               // dd($this->clientes);
               // return $this->clientes;
            } else {
                $this->clientes = \App\Models\Entidad::query()
                                            ->whereHas('productosVendedores', function ($query) {
                                                $query
                                                    ->where('producto_id', '3');
                                            })
                                            ->orderBy('razon_social')
                                            ->get();
            }

            return $this->clientes;
        }

        #[Computed]
        public function vendedores()
        {
            return \App\Models\User::select('id', 'name')->get();
        }

        #[Computed]
        public function bancos()
        {
            if ($this->esVendedor()) {
                return \App\Models\Entidad::bancos()->select('id', 'razon_social')
                                            ->where('razon_social', 'BCRA COM A 6401')->get();
            } else {
                return \App\Models\Entidad::bancos()->select('id', 'razon_social')->get();
            }
        }

        public function dehydrate()
        {
            $this->fechaboleto =  date('Y-m-d');;
        }

        public function updatedEntidadid(): void
        {
            $comprador = \App\Models\Entidad::select('tipo_operacion', 'porcentaje_comision', 'cuit', 'tipo_entidad_id')->find($this->entidadid);
//            $this->porcentajecomprador = $comprador->porcentaje_comision;
            $this->observacion = $comprador->tipo_entidad_id == 1 ? 'CUIT: '. $comprador->cuit : $this->observacion;

            $vendedor_select = \App\Models\EntidadProductoVendedor::where('producto_id', '3')
                                                                    ->where('entidad_id', $this->entidadid)->get()->toArray();
            //dd($vendedor_select[0]['vendedor_id']);
            $this->vendedorid = $vendedor_select[0]['vendedor_id'];

        }

        public function grabarMinuta6401()
        {
            $this->validar();

        }

        public function validar()
        {
            return $this->validate([

                                    'numeroboleto' => ['required'],
                                    'estadoid' => ['required'],
                                    'fechaboleto' => ['required'],
                                    'entidadid' => ['required'],
                                    'bancoid' => ['required'],
                                    'periododmes' => ['required'],
                                    'periododanio' => ['required'],
                                    'periodohmes' => ['required'],
                                    'periodohanio' => ['required'],
                                    'observacion' => ['required'],
                                    'comisiondolares' => ['required'],
                                    'totalcomisiondolares' => ['required'],
                                    'tipocambio' => ['required'],
                                    'totalminuta' => ['required'],

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
    <flux:modal name="minuta-cambio-crear-modal" class="min-w-[64rem]">
        <form wire:submit="grabarMinuta6401" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-800" size="lg">Datos de la Minuta 6401
                </flux:heading>
            </div>
            <flux:card>
                {{-- nro estado y fecha --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Número --}}
                    <div class="w-1/4">
                        <flux:input wire:model="numeroboleto" label="Número" />
                    </div>
                    {{-- Estado --}}
                    <div class="w-1/2">
                        <flux:select searchable wire:model="estadoid" label="Estado" placeholder="Seleccione un Estado">
                            @foreach ($this->estados as $estado)
                                @if ($estado->id == 1)  {{$this->estadoid = 1}} @endif
                                <flux:select.option value="{{ $estado->id }}" wire:key="{{ $estado->id }}">{{ $estado->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Fecha --}}
                    <div class="w-1/2">
                        <flux:date-picker type="input" label="Fecha" wire:model="fechaboleto" />
                    </div>
                </div>
                {{-- 1° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Cliente --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="entidadid" label="Cliente"
                                     placeholder="Seleccione un Cliente">
                            @foreach ($this->clientes as $cliente)
                                <flux:select.option value="{{ $cliente->id }}"
                                                    wire:key="{{ $cliente->id }}">{{ $cliente->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Vendedor --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="bancoid" label="Banco"
                                     placeholder="Seleccione un Banco">
                            @foreach ($this->bancos as $banco)
                                <flux:select.option value="{{ $banco->id }}"
                                                    wire:key="{{ $banco->id }}">{{ $banco->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                <flux:separator class="my-4" />
                {{-- 2° fila --}}
                <div class="ml-32 flex w-3/4 flex-row justify-between space-x-4 text-left">
                    <div class="w-1/2 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos desde mes--}}
                        <div class="w-28">
                            <flux:label>Periodo desde</flux:label>
                        </div>
                        <div class="w-12">
                            <flux:input wire:model="periododmes" maxlength="1" />
                        </div>
                        {{-- Períodos desde año--}}
                        <div class="w-24">
                            <flux:input wire:model="periododanio" maxlength="4"/>
                        </div>
                    </div>
                    <div class="w-1/2 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos hasta mes--}}
                        <div class="w-28">
                            <flux:label>Periodo hasta</flux:label>
                        </div>
                        <div class="w-12">
                            <flux:input wire:model="periodohmes" maxlength="1" />
                        </div>
                        {{-- Períodos hasta año--}}
                        <div class="w-24">
                            <flux:input wire:model="periodohanio" maxlength="4"/>
                        </div>
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- observación --}}
                    <div class="w-full">
                        <flux:textarea rows="2" wire:model.enter.live="observacion" label="Observación" />
                    </div>
                </div>
                <flux:card class="mt-2">
                    {{-- 4ª fila --}}
                    <div class="flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Vendedor --}}
                        <div class="w-1/3">
                            <flux:select :disabled="true" wire:model="vendedorid" label="Vendedor">
                                <flux:select.option>-</flux:select.option>
                                @foreach($this->vendedores as $vendedor)
                                    <flux:select.option value="{{$vendedor->id}}" wire:key="{{$vendedor->id}}">{{$vendedor->name}}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        {{-- comisión dolares --}}
                        <div class="w-1/3">
                            <flux:input wire:model="comisiondolares" label="Comisión U$S" />
                        </div>
                        {{-- Total comisión en dolares --}}
                        <div class="w-1/5">
                            <flux:input wire:model="totalcomisiondolares" label="Total Comisión U$S" />
                        </div>
                    </div>

                    <hr class="ml-78 my-4" style="border: none; height: 2px; background-color: #333; width: 65%;">
                    {{-- 5ª fila --}}
                    <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Tipo de cambio --}}
                        <div class="ml-78 w-1/3">
                            <flux:input wire:model.model.live="tipocambio" wire:keydown.enter="entertipocambio"
                                        label="Tipo de Cambio" />
                        </div>
                        {{-- equivalente --}}
                        <div class="w-1/3">
                            <flux:input icon="currency-dollar" wire:model="totalminuta" readonly
                                        label="Total Comisión $" />
                        </div>
                    </div>
                </flux:card>
            </flux:card>

            {{-- buttones--}}
            <div class="flex justify-end">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button wire:click="cancel()" variant="ghost" class="cursor-pointer">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary"
                             wireclass="cursor-pointer ms-2">Grabar Minuta
                </flux:button>
            </div>
        </form>
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

    </flux:modal> {{-- The whole world belongs to you. --}}

</div>
