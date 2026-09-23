<?php

    use Flux\Flux;
    use Livewire\Attributes\Computed;
    use Livewire\Attributes\On;
    use Livewire\Component;

    new class extends Component {
        public $maximo;
        public $fechaboleto;
        public $estadoid;
        public $entidadid;
        public $selectProducto;
        public $productoNombre;
        public $productoId;
        public $entidad_cliente_id;
        public $numeroboleto;
        public $arca = 'ARCA';
        public $anioDesde;
        public $anioHasta;
        public $periodocantidad;
        public $observacion;
        public $comisiondolares;
        public $totalcomisiondolares;
        public $tipocambio;
        public $totalminuta;
        public $nombre_vendedor;
        public $vendedorid;
        public $isView = true;
        public $bancoVendedor;
        public $bancoVendedorId;
        public $isVendedor = 0;

        public $totalminutaaux;


        #[On('minutaCrear')]
        public function minutaCrear($producto)
        {
            if ($this->esVendedor()) {
                $this->isVendedor = 1;
            }
            $this->selectProducto = json_decode($producto);
            //$this->producto = $datosproducto;
            $this->productoNombre = $this->selectProducto->nombre;
            $this->productoId     = $this->selectProducto->id;

            //dd($this->productoNombre);
        }

        private function esVendedor(): bool
        {
            return auth()->user()->hasRole('vendedor');
        }

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::select('id', 'nombre')->get();
        }

        #[Computed]
        public function clientes()
        {
            //$productoId = 3;
            if ($this->isVendedor) {
                $clientes = \App\Models\Entidad::query()->whereHas('productosVendedores', function($query) {
                    $query->where('producto_id', $this->productoId)->where('vendedor_id', auth()->id());
                })->orderBy('razon_social')->get(); //toSql();
            }
            else {
                $clientes = \App\Models\Entidad::query()->whereHas('productosVendedores', function($query) {
                    $query->where('producto_id', $this->productoId);
                })->orderBy('razon_social')->get();
            }

            return $clientes;
        }

        #[Computed]
        public function vendedores()
        {
            return \App\Models\User::select('id', 'name')->get();
        }

        public function dehydrate()
        {
            $this->fechaboleto = date('Y-m-d');;
        }

        public function updatedEntidadid(): void
        {
            $this->comisiondolares = 0;
            $comprador             = \App\Models\Entidad::select('tipo_operacion', 'porcentaje_comision', 'cuit', 'tipo_entidad_id')
                                                        ->find($this->entidadid);

            $this->observacion = $comprador->tipo_entidad_id == 1 ? 'CUIT: ' . $comprador->cuit : $this->observacion;
            $vendedor_select   = \App\Models\EntidadProductoVendedor::where('producto_id', $this->productoId)
                                                                    ->where('entidad_id', $this->entidadid)->get()
                                                                    ->toArray();

            $this->vendedorid = $vendedor_select[ 0 ][ 'vendedor_id' ];

            $comisinoentidad = \App\Models\EntidadHonorarioProducto::where('entidad_id', $this->entidadid)
                                                                   ->whereHas('honorario_producto', function($query) {
                                                                       $query->where('producto_id', $this->productoId);
                                                                   })->with('honorario_producto')->first();

            $this->comisiondolares = $comisinoentidad->honorario_producto->importe;
            $this->procesarperiodo();

        }

        public function updatedAnioDesde()
        {
            $this->procesarperiodo();
        }

        public function updatedAnioHasta()
        {
            $this->procesarperiodo();
        }

        public function updatedComisiondolares()
        {
            $this->procesarperiodo();
        }

        public function updatedTipocambio()
        {
            $this->procesarperiodo();
        }

        public function procesarperiodo()
        {
            $this->periodocantidad = 0;

            if ($this->anioDesde != '' && $this->anioHasta != '') {

                $this->validate([
                                    'anioDesde' => [
                                        'required',
                                        'numeric',
                                        'min:2000',
                                        'max:2040'
                                    ],
                                    'anioHasta' => [
                                        'required',
                                        'numeric',
                                        'min:2000',
                                        'max:2040'
                                    ],
                                ], [
                                    'anioDesde'         => 'Solo números',
                                    'anioDesde.numeric' => 'Solo números',
                                    'anioDesde.min'     => 'mayos 2000',
                                    'anioDesde.max'     => 'menor 2040',

                                    'anioHasta'         => 'Solo números',
                                    'anioHasta.numeric' => 'Solo números',
                                    'anioHasta.min'     => 'mayos 2000',
                                    'anioHasta.max'     => 'menor 2040',
                                ]);

                if ($this->anioDesde > $this->anioHasta) {
                    $this->validate([
                                        'anioDesde' => 'required|numeric',
                                        'anioHAsta' => 'required|numeric|gt:anioDesde',
                                    ], [
                                        'anioHasta' => 'Año hasta, tiene que se mayor',
                                    ]);
                }

                if ($this->anioDesde <= $this->anioHasta) {

                    if ($this->anioDesde == $this->anioHasta) {
                        $this->periodocantidad = 1;
                        // dd($this->anioDesde .' = '. $this->anioHasta);
                    }
                    else if ($this->anioDesde < $this->anioHasta) {
                        for ($i = $this->anioDesde - 1; $i < $this->anioHasta; $i++) {
                            $this->periodocantidad++;
                        }
                    }
                }

                if ($this->periodocantidad > 0 && $this->comisiondolares > 0) {
                    $this->totalcomisiondolares = $this->periodocantidad * $this->comisiondolares;
                    if ($this->tipocambio > 0) {
                        $this->totalminutaaux = $this->tipocambio * $this->totalcomisiondolares;
                        $this->totalminuta    = round($this->totalminutaaux, 2);
                    }
                }
            }
        }

        public function grabarMinuta()
        {
            //dd($this->bancoid);
            $this->validar();

            $this->maximo = \App\Models\Minuta::maxMinuta($this->productoId)->max('numero') + 1;

            DB::transaction(function() {
                try {
                    $cargo = \App\Models\Minuta::create([
                                                            'numero'                   => $this->maximo,
                                                            'estado_id'                => $this->estadoid,
                                                            'producto_id'              => $this->productoId,
                                                            'fecha'                    => $this->fechaboleto,
                                                            'entidad_cliente_id'       => $this->entidadid,
                                                            'arca'                     => 1,
                                                            'anio_desde'               => $this->anioDesde,
                                                            'anio_hasta'               => $this->anioHasta,
                                                            'anio_cantidad'            => $this->periodocantidad,
                                                            'observacion'              => $this->observacion,
                                                            'tipo_cambio'              => $this->tipocambio,
                                                            'importe_comision_unidad'  => $this->comisiondolares,
                                                            'importe_comision_dolares' => $this->totalcomisiondolares,
                                                            'importe_comision'         => $this->totalminuta,
                                                            'usuario_vendedor_id'      => $this->vendedorid,
                                                        ]);
                }
                catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            });

            $this->reset();
            Flux::modal('minuta-crear-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::minutas.formularios.index');
        }

        public function cancel(): void
        {
            $this->reset();
            Flux::modal('minuta-crear-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::minutas.formularios.index');
        }

        public function validar()
        {
            $this->validate([
                                'estadoid' => ['required','exists:estados,id'],
                                'fechaboleto' => ['required' ],
                                'entidadid' => ['required','exists:entidads,id'],
                                'anioDesde' => ['required'],
                                'anioHasta' => ['required'],
                                'observacion' => ['nullable'],
                                'comisiondolares' => ['required','regex:/^[\d.]+$/'],
                                'totalcomisiondolares' => ['required','regex:/^[\d.]+$/'],
                                'tipocambio' => ['required','regex:/^[\d.]+$/'],
                                'totalminuta' => ['required','regex:/^[\d.]+$/'],
                            ], [
                                'estadoid'    => 'estado es requerido',
                                'fechaboleto' => 'fecha es requerido',
                                'entidadid'   => 'entidad es requerido',
                                'bancoid'     => 'banco es requerido',

                                'anioDesde'         => 'período año desde es requerido',
                                'anioHasta'         => 'período año hasta es requerido',
                                'comisiondolares'      => 'es requerido',
                                'totalcomisiondolares' => 'es requerido',
                                'tipocambio'           => 'es requerido',
                                'totalminuta'          => 'es requerido',
                            ]);
        }

    };
?>

<div>
    <flux:modal name="minuta-crear-modal" class="min-w-[64rem]">
        <form wire:submit="grabarMinuta" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-800"
                              size="lg">Datos de la Minuta - {{$this->productoNombre}}
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
                                @if ($estado->id == 1)
                                    {{$this->estadoid = 1}}
                                @endif
                                <flux:select.option value="{{ $estado->id }}"
                                                    wire:key="{{ $estado->id }}">{{ $estado->nombre }}</flux:select.option>
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
                    {{-- ARCA --}}
                    <div class="w-1/2">
                        <div>
                            <flux:input :disabled="true" wire:model="arca" label="ARCA" />
                        </div>
                    </div>
                </div>
                <flux:separator class="my-4" />
                {{-- 2° fila --}}
                <div class="ml-32 flex flex-row justify-between space-x-4 text-left">
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- año desde--}}
                        <div class="w-28 text-right">
                            <flux:label>Año desde</flux:label>
                        </div>
                        {{-- Año desde--}}
                        <div class="w-24">
                            <flux:input wire:model.live="anioDesde" maxlength="4" />
                        </div>
                    </div>
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- Año hasta--}}
                        <div class="w-28 text-right">
                            <flux:label>Periodo hasta</flux:label>
                        </div>
                        <div class="w-24">
                            <flux:input wire:model.live="anioHasta" maxlength="4" />
                        </div>
                    </div>
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos hasta mes--}}
                        <div class="w-24 text-right">
                            <flux:label>Total</flux:label>
                        </div>
                        {{-- totalPeríodos hasta año--}}
                        <div class="w-24">
                            <flux:input :disabled="true" wire:model="periodocantidad" maxlength="4" />
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
                                    <flux:select.option value="{{$vendedor->id}}"
                                                        wire:key="{{$vendedor->id}}">{{$vendedor->name}}</flux:select.option>
                                @endforeach
                            </flux:select>
                        </div>
                        {{-- comisión dolares --}}
                        <div class="w-1/3">
                            <flux:input wire:model.live="comisiondolares" label="Comisión U$S" />
                        </div>
                        {{-- Total comisión en dolares --}}
                        <div class="w-1/5">
                            <flux:input :disabled="true" wire:model="totalcomisiondolares" label="Total Comisión U$S" />
                        </div>
                    </div>

                    <hr class="ml-78 my-4" style="border: none; height: 2px; background-color: #333; width: 65%;">
                    {{-- 5ª fila --}}
                    <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Tipo de cambio --}}
                        <div class="ml-78 w-1/3">
                            <flux:input wire:model.model.live="tipocambio"
                                        label="Tipo de Cambio" />
                        </div>
                        {{-- total minuta --}}
                        <div class="w-1/3">
                            <flux:input :disabled="true" icon="currency-dollar" wire:model="totalminuta" readonly
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
