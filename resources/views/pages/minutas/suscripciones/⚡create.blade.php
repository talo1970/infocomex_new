<?php

    use Flux\Flux;
    use Livewire\Attributes\Computed;
    use Livewire\Attributes\On;
    use Livewire\Component;

    new class extends Component {

        public $isVendedor = 0;
        public $selectProducto;
        public $productoNombre;
        public $productoId;
        public $numerosuscripcion;
        public $estadoid;
        public $fechaboleto;

        public $entidadid;
        public int $plazo;
        public $inicio;
        public $fecha_vto;
        public $valor;

        public $cantidaSuscriptor;
        public array $atributos = [];


        #[On('minutaCrear')]
        public function minutaCrear($producto, $valor)
        {
            if ($this->esVendedor()) {
                $this->isVendedor = 1;
            }
            $this->selectProducto = json_decode($producto);
            $this->productoNombre = $this->selectProducto->nombre;
            $this->productoId     = $this->selectProducto->id;
            $this->valor = $valor;
            $this->cantidaSuscriptor = 0;
            $this->fecha_vto = \Carbon\Carbon::now()->addMonth()->format('Y-m-d');

            //dd($this->productoId, $valor);

/*
            $this->atributos[] = [
                'inicio' => $this->fecha,
                'plazo'=> 1,
                'fin' => null,
                'importe' => $this->importe
            ];
            */
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
            if ($this->isVendedor) {
                $clientes = \App\Models\Entidad::query()->whereHas('productosVendedores', function($query) {
                    $query->where('producto_id', $this->productoId)->where('vendedor_id', auth()->id());
                })->orderBy('razon_social')->toSql(); //toSql();
                // return $this->clientes;
            }
            else {
                $clientes = \App\Models\Entidad::query()->whereHas('productosVendedores', function($query) {
                    $query->where('producto_id', $this->productoId);
                })->orderBy('razon_social')->get();
                //dd($clientes);
            }

            return $clientes;
        }

        #[Computed]
        public function tipoDocumentos()
        {
            return \App\Models\TipoDocumento::select('id', 'nombre')->get();
        }

        #[Computed]
        public function referencias()
        {
            return \App\Models\Referencia::select('id', 'nombre')->get();
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

        public function updatedPlazo()
        {
            $this->validate(['plazo' => ['required', 'numeric', 'min:1']
                            ]);

                $this->fecha_vto = \Carbon\Carbon::parse($this->fechaboleto)->addMonths($this->plazo)->format('Y-m-d');

        }

        public function updatedEntidadid(): void
        {
            $this->comisiondolares = 0;
            $comprador             = \App\Models\Entidad::select('porcentaje_comision', 'cuit', 'tipo_entidad_id')
                                                        ->find($this->entidadid);

            //dd($comprador);
            $this->observacion = $comprador->tipo_entidad_id == 1 ? 'CUIT: ' . $comprador->cuit : $this->observacion;
            $vendedor_select   = \App\Models\EntidadProductoVendedor::where('producto_id', $this->productoId)
                                                                    ->where('entidad_id', $this->entidadid)->get()
                                                                    ->toArray();

            $this->vendedorid = $vendedor_select[ 0 ][ 'vendedor_id' ];
            //dd($this->vendedorid);
            $comisinoentidad = \App\Models\EntidadHonorarioProducto::where('entidad_id', $this->entidadid)
                                                                   ->whereHas('honorario_producto', function($query) {
                                                                       $query->where('producto_id', $this->productoId);
                                                                   })->with('honorario_producto')->first();
            //dd($comisinoentidad);

            $this->comisiondolares = $comisinoentidad != null ? $comisinoentidad->honorario_producto->importe: 0;
            //dd($this->comisiondolares);
            //$this->procesarperiodo();
        }
/*
        public function procesarperiodo()
        {
            if ($this->plazo > 0){

            }

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
*/
        public function cancel(): void
        {
            $this->reset();
            Flux::modal('suscripcion-crear-modal')->close();
            $this->dispatch('refreshComponent')->to('pages::minutas.suscripciones.index');
        }






/*
cuando graba la suscripcion
    $novedad = Novedades::create([
    'fecha'        => Carbon::parse(now())->isoFormat('Y-MM-DD'),
    'titulo'       => $this->titulo,
    'comentario'   => $this->comentario,
    'is_publicado' => $this->is_publicado,
    ]);

    foreach ($this->archivos_tmp as $newArchivo) {
    $novedad->archivos()->create([
    'nombre'    => $newArchivo[ 'nombre' ],
    'path'      => $newArchivo[ 'path' ],
    'extension' => $newArchivo[ 'extension' ],
    ]);
    }
*/
    };
?>

<div>

    <flux:modal name="suscripcion-crear-modal" class="min-w-[64rem]">
        <form wire:submit="grabarSuscripcion" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-800"
                              size="lg">Datos de la Suscripción - {{--$this->productoNombre--}}
                </flux:heading>
            </div>
            <flux:card>
                {{-- nro estado y fecha --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Número --}}
                    <div class="w-1/4">
                        <flux:input wire:model="numerosuscripcion" label="Número" />
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
                    <div class="w-3/4">
                        <flux:select variant="listbox" searchable wire:model.live="entidadid" label="Cliente"
                                     placeholder="Seleccione un Cliente">
                            @foreach ($this->clientes as $cliente)
                                <flux:select.option value="{{ $cliente->id }}"
                                                    wire:key="{{ $cliente->id }}">{{ $cliente->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Tipo documento --}}
                    <div class="w-1/4">
                        <flux:select variant="listbox" searchable wire:model.live="tipodocid" label="Tipo Documento"
                                     placeholder="Seleccione un Cliente">
                            @foreach ($this->tipoDocumentos as $tipo)
                                <flux:select.option value="{{ $tipo->id }}"
                                                    wire:key="{{ $tipo->id }}">{{ $tipo->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                <flux:separator class="my-4" />
                {{-- 2° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-2 text-left">
                    {{-- plazo--}}
                    <div class="w-16">
                        <flux:input wire:model.live="plazo" maxlength="4" label="Plazo"/>
                    </div>
                    {{-- fecha vto --}}
                    <div class="w-42">
                        <flux:date-picker :disabled="true" type="input" wire:model="fecha_vto" label="Vencimiento"/>
                    </div>
                    {{-- referencia --}}
                    <div class="w-3/4">
                        <flux:select variant="listbox" searchable wire:model.live="referenciaid" label="Referencia"
                                     placeholder="Seleccione una Referencia">
                            @foreach ($this->referencias as $referencia)
                                <flux:select.option value="{{ $referencia->id }}"
                                                    wire:key="{{ $referencia->id }}">{{ $referencia->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- observación --}}
                    <div class="w-full">
                        <flux:textarea rows="1" wire:model.enter.live="observacion" label="Observación" />
                    </div>
                </div>
                {{-- Suscriptores--}}
                @if (!empty($this->plazo))
                        {{$this->fecha_vto}}
                @endif

                <flux:card class="mt-2">
                    <div class="-mt-6 h-48 overflow-auto">
                        <livewire:pages::minutas.suscripciones.suscriptores.index :minuta="null" :fecha="$this->fecha_vto"/>
                    </div>
                </flux:card>
                {{-- 5ª fila --}}
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

    </flux:modal>

</div>
