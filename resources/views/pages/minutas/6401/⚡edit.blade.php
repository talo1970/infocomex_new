<?php

    use Flux\Flux;
    use Livewire\Attributes\Computed;
    use Livewire\Attributes\On;
    use Livewire\Component;

    new class extends Component {
        public $minuta;
        public $isVendedor = 0;
        public $productoId = 3;
        public $numeroboleto;
        public $estadoid;
        public $fechaboleto;
        public $entidadid;
        public $bancoVendedor;
        public $bancoVendedorId;
        public $bancoid;
        public $periododmes;
        public $periododanio;
        public $periodohmes;
        public $periodohanio;
        public $periodocantidad;
        public $observacion;
        public $vendedorid;
        public $comisiondolares;
        public $totalcomisiondolares;
        public $tipocambio;
        public $totalminuta;

        #[On('minutaEdit')]
        public function minutaEdit(\App\Models\Minuta $minuta): void
        {
            $this->minuta = $minuta;
            if ($this->esVendedor()) {
                $this->isVendedor = 1;
            }

            $this->numeroboleto = $this->minuta->numero;
            $this->fechaboleto  = $this->minuta->fecha;
            $this->estadoid     = $this->minuta->estado_id;

            $this->entidadid = $this->minuta->entidad_cliente_id;
            if ($this->isVendedor == 0) {
                $this->bancoid = $this->minuta->bcra_id;
            }
            else {
                //ver si lo busco lo que est`cargado por si la carga la hace Oscer y le pone otra entidad
                $this->bancoVendedor   = 'BCRA COM A 6401';
                $this->bancoVendedorId = $this->minuta->bcra_id;
            }
//dd($this->minuta);
            $desde                      = explode('-', $this->minuta->periodo_desde);
            $this->periododmes          = $desde[ 0 ];
            $this->periododanio         = $desde[ 1 ];
            $hasta                      = explode('-', $this->minuta->periodo_hasta);
            $this->periodohmes          = $hasta[ 0 ];
            $this->periodohanio         = $hasta[ 1 ];
            $this->periodocantidad      = $this->minuta->periodo_cantidad;
            $this->observacion          = $this->minuta->observacion;
            $this->vendedorid           = $this->minuta->usuario_vendedor_id;
            $this->comisiondolares      = $this->minuta->importe_comision_unidad;
            $this->totalcomisiondolares = $this->minuta->importe_comision_dolares;
            $this->tipocambio           = $this->minuta->tipo_cambio;
            $this->totalminuta          = $this->minuta->importe_comision;
        }

        private function esVendedor(): bool
        {
            return auth()->user()->hasRole('vendedor');
        }

        #[Computed]
        public function clientes()
        {
            //return \App\Models\Entidad::clientes()->select('id', 'razon_social')->get();
            $productoId = 3;
            if ($this->isVendedor) {
                $clientes = \App\Models\Entidad::query()->whereHas('productosVendedores', function($query) {
                        $query->where('producto_id', '3')->where('vendedor_id', auth()->id());
                    })->orderBy('razon_social')->get(); //toSql();
            }
            else {
                $clientes = \App\Models\Entidad::query()->whereHas('productosVendedores', function($query) {
                        $query->where('producto_id', '3');
                    })->orderBy('razon_social')->get();
            }

            return $clientes;
        }

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::select('id', 'nombre')->get();
        }

        #[Computed]
        public function vendedores()
        {
            return \App\Models\User::select('id', 'name')->get();
        }

        #[Computed]
        public function bancos()
        {
            if ( ! $this->isVendedor) {
                //return \App\Models\Entidad::bancos()->select('id', 'razon_social')
                //                            ->where('razon_social', 'BCRA COM A 6401')->get();

                //} else {
                return \App\Models\Entidad::bancos()->select('id', 'razon_social')->get();
            }
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

            $vendedor_select  = \App\Models\EntidadProductoVendedor::where('producto_id', '3')
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

        public function updatedPeriododmes(): void
        {
            $this->procesarperiodo();
        }

        public function updatedPeriododanio()
        {
            $this->procesarperiodo();
        }

        public function updatedPeriodohmes()
        {
            $this->procesarperiodo();
        }

        public function updatedPeriodohanio()
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

            // periododmes
            // periododanio

            // periodohmes
            // periodohanio

            if ($this->periododmes != '' && $this->periodohmes != '' && $this->periododanio != '' && $this->periodohanio != '') {
                $this->validate([
                                    'periododmes' => [
                                        'required',
'numeric',
'min:1',
'max:4'
                                    ],
'periododanio' => [
    'required',
'numeric',
'min:2000',
'max:2040'
],
'periodohmes' => [
    'required',
'numeric',
'min:1',
'max:4'
],
'periodohanio' => [
    'required',
    'numeric',
    'min:2000',
    'max:2040'
],
                                ], [
                                    'periododmes.numeric' => 'solo número',
                                    'periododmes.nim'     => 'mayor 0',
                                    'periododmes.max'     => 'menor 5',

                                    'periododanio'     => 'Solo números',
                                    'periododanio.min' => 'mayos 2000',
                                    'periododanio.max' => 'menor 2040',

                                    'periodohmes.numeric' => 'Solo número',
                                    'periodohmes.nim'     => 'mayor 0',
                                    'periodohmes.max'     => 'menor 5',

                                    'periodohanio'         => 'Solo números',
                                    'periodohanio.numeric' => 'Solo números',
                                    'periodohanio.min'     => 'mayos 2000',
                                    'periodohanio.max'     => 'menor 2040',
                                ]);

                if ($this->periododanio > $this->periodohanio) {
                    $this->validate([
                                        'periododanio' => 'required|numeric',
                                        'periodohanio' => 'required|numeric|gt:periododanio',
                                    ], [
                                        'periodohanio' => 'Año hasta, tiene que se mayor',
                                    ]);

                }

                if ($this->periododanio <= $this->periodohanio) {
                    if ($this->periododmes == $this->periodohmes && $this->periododanio == $this->periodohanio) {
//                    dump('1     -'.$this->periododmes.' - '.$this->periodohmes.' - '.$this->periododanio.' - '.$this->periodohanio);
                        $this->periodocantidad = 1;

                    }
                    else if ($this->periododmes < $this->periodohmes && $this->periododanio == $this->periodohanio) {
//                    dump('3     -'.$this->periododmes.' - '.$this->periodohmes.' - '.$this->periododanio.' - '.$this->periodohanio);
                        for ($i = $this->periododmes - 1; $i < $this->periodohmes; $i++) {
                            $this->periodocantidad++;
//                        dump('3-a - '.$this->periodocantidad);
                        }
                    }
                    else if ($this->periododanio < $this->periodohanio) {
//                   dump('4  -  '.$this->periodocantidad .' -- '.$this->periododmes.' - '.$this->periodohmes.' - '.$this->periododanio.' - '.$this->periodohanio);
                        for ($i = $this->periododmes; $i < 4; $i++) {
                            $this->periodocantidad++;
//                        dump('4-a  -  '.$this->periodocantidad);
                        }

                        for ($i = 1; $i < ($this->periodohanio - $this->periododanio); $i++) {
                            $this->periodocantidad = $this->periodocantidad + 4;
//                        dump('4-b  -  '.$this->periodocantidad);
                        }
//                    dump('4-bb  -  '.$this->periodocantidad);
                        for ($i = 0; $i <= $this->periodohmes; $i++) {
                            $this->periodocantidad++;
//                        dump('4-c  -  '.$this->periodocantidad);
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

        public function cancel(): void
        {
            $this->reset();
            $this->redirectRoute('minutas.6401.index', navigate: true);
        }

        public function grabarMinuta6401()
        {
            //dd($this->bancoid);
            $this->validar();


            $this->minuta->estado_id                = $this->estadoid;
            $this->minuta->fecha                    = $this->fechaboleto;
            $this->minuta->entidad_cliente_id       = $this->entidadid;
            $this->minuta->bcra_id                  = $this->isVendedor != 0 ? $this->bancoVendedorId : $this->bancoid;
            $this->minuta->periodo_desde            = $this->periododmes . '-' . $this->periododanio;
            $this->minuta->periodo_hasta            = $this->periodohmes . '-' . $this->periodohanio;
            $this->minuta->periodo_cantidad         = $this->periodocantidad;
            $this->minuta->observacion              = $this->observacion;
            $this->minuta->tipo_cambio              = $this->tipocambio;
            $this->minuta->importe_comision_unidad  = $this->comisiondolares;
            $this->minuta->importe_comision_dolares = $this->totalcomisiondolares;
            $this->minuta->importe_comision         = $this->totalminuta;
            $this->minuta->usuario_vendedor_id      = $this->vendedorid;

            DB::transaction(function() {
                try {
                    if ($this->minuta->isDirty()) {
                        $this->minuta->save();
                        Flux::toast(heading: 'Editar', text: 'grabo por esta sucio', variant: 'success', position: 'top end');
                    }
                    else {
                        Flux::toast(heading: 'Editar', text: ' NO ESTA SUCIA PASO PERO NO GRABO', variant: 'success', position: 'top end');
                    }
                }
                catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            });

            $this->dispatch('refreshComponent')->to('pages::minutas.6401.index');
            $this->reset();
            Flux::modal('editar-6401-modal')->close();
        }

        public function validar()
        {
            $this->validate([
                                'estadoid' => [
                                    'required',
'exists:estados,id'
                                ],
'fechaboleto' => [ 'required' ],
'entidadid' => [
    'required',
'exists:entidads,id'
],
'bancoid' => [ 'required_if:isVendedor,0' ],
'periododmes' => [ 'required' ],
'periododanio' => [ 'required' ],
'periodohmes' => [ 'required' ],
'periodohanio' => [ 'required' ],
'observacion' => [ 'nullable' ],
'comisiondolares' => [
    'required',
'regex:/^[\d.]+$/'
],
'totalcomisiondolares' => [
    'required',
'regex:/^[\d.]+$/'
],
'tipocambio' => [
    'required',
'regex:/^[\d.]+$/'
],
'totalminuta' => [
    'required',
    'regex:/^[\d.]+$/'
],
                            ], [
                                'estadoid'    => 'estado es requerido',
                                'fechaboleto' => 'fecha es requerido',
                                'entidadid'   => 'entidad es requerido',
                                'bancoid'     => 'banco es requerido',

                                'periododmes'          => 'período mes desde es requerido',
                                'periododanio'         => 'período año desde es requerido',
                                'periodohmes'          => 'período mes hasta es requerido',
                                'periodohanio'         => 'período año hasta es requerido',
                                'comisiondolares'      => 'es requerido',
                                'totalcomisiondolares' => 'es requerido',
                                'tipocambio'           => 'es requerido',
                                'totalminuta'          => 'es requerido',
                            ]);
        }

    };
?>


<div>
    <flux:modal name="editar-6401-modal" class="min-w-[64rem]">
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
                    {{-- banco --}}
                    <div class="w-1/2">
                        @if ($this->isVendedor)
                            <div>
                                <flux:input :disabled="true" wire:model="bancoVendedor" label="Banco" />
                            </div>
                        @else
                            <flux:select variant="listbox" searchable wire:model.live="bancoid" label="Banco"
                                         placeholder="Seleccione un Banco">
                                @foreach ($this->bancos as $banco)
                                    <flux:select.option value="{{ $banco->id }}"
                                                        wire:key="{{ $banco->id }}">{{ $banco->razon_social }}</flux:select.option>
                                @endforeach
                            </flux:select>
                        @endif
                    </div>
                </div>
                <flux:separator class="my-4" />
                {{-- 2° fila --}}
                <div class="ml-32 flex flex-row justify-between space-x-4 text-left">
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos desde mes--}}
                        <div class="w-28 text-right">
                            <flux:label>Periodo desde</flux:label>
                        </div>
                        <div class="w-12">
                            <flux:input wire:model.live="periododmes" maxlength="1" />
                        </div>
                        {{-- Períodos desde año--}}
                        <div class="w-24">
                            <flux:input wire:model.live="periododanio" maxlength="4" />
                        </div>
                    </div>
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos hasta mes--}}
                        <div class="w-28 text-right">
                            <flux:label>Periodo hasta</flux:label>
                        </div>
                        <div class="w-12">
                            <flux:input wire:model.live="periodohmes" maxlength="1" />
                        </div>
                        {{-- Períodos hasta año--}}
                        <div class="w-24">
                            <flux:input wire:model.live="periodohanio" maxlength="4" />
                        </div>
                    </div>
                    <div class="w-1/3 flex flex-row items-start space-x-4 text-left">
                        {{-- Períodos hasta mes--}}
                        <div class="w-24 text-right">
                            <flux:label>Total</flux:label>
                        </div>
                        {{-- totalPeríodos hasta año--}}
                        <div class="w-24">
                            <flux:input wire:model="periodocantidad" maxlength="4" />
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
                            {{-- wire:keydown.enter="entertipocambio"--}}
                            <flux:input wire:model.model.live="tipocambio" label="Tipo de Cambio" />
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
