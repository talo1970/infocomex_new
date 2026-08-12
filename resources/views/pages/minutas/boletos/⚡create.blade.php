<?php

    use Livewire\Attributes\Computed;
    use Livewire\Component;
    use Illuminate\Support\Number;
    use Carbon\Carbon;
    use Livewire\Attributes\Rule;


    new class extends Component {

        public $maximo;
        public $fechaboleto;
        public $estadoid;

        public $compradorid;
        public $vendedorid;
        public $tipovendedor;
        public $tipocomprador;
        // 2 fila
        public $monedaid;
        public $valorid;
        public $claseid;
        // 3 fila
//        #[\Livewire\Attributes\Validate('regex:/^\d+(,\d+)?$/')]
//        #[\Livewire\Attributes\Validate('required', 'regex:/^\d+(,\d+)?$/')]
        public $importe;

        //#[\Livewire\Attributes\Validate('required','regex:/^[0-9]+(?:\.[0-9]+)?$/')]
        public $tipocambio;

        public $equivalente;
        // 4 fila
        public $referenciaid;
        // 5 fila
        public $observacion;
        // 6 fila
        public $porcientovendedor;
        public $porcentajevendedore;
        public $comisionvendedor;
        // 7 fila
        public $porcientocomprador;
        public $porcentajecomprador;
        public $comisioncomprador;
        public $totalminuta;

        public $equivalenteaux = 0;
        public $comisionvendedoraux = 0;
        public $comisioncompradoraux = 0;
        public $totalminutaaux = 0;

        #[Computed]
        public function estados()
        {
            return \App\Models\Estado::select('id', 'nombre')->get();
        }

        #[Computed]
        public function compradores()
        {
            return \App\Models\Entidad::select('id', 'razon_social')->get();
        }

        #[Computed]
        public function vendedores()
        {
            return \App\Models\Entidad::select('id', 'razon_social')->get();
        }

        #[Computed]
        public function monedas()
        {
            return  \App\Models\Moneda::select('id', 'nombre', 'seleccion')->orderByDesc('seleccion')->get();
        }

        #[Computed]
        public function valores()
        {
            return \App\Models\Valor::select('id', 'nombre', 'seleccion')->orderByDesc('seleccion')->get();
        }

        #[Computed]
        public function clases()
        {
            return \App\Models\Clase::select('id', 'nombre', 'seleccion')->orderByDesc('seleccion')->get();
        }

        #[Computed]
        public function referencias()
        {
            return \App\Models\Referencia::select('id', 'nombre', 'seleccion')->orderByDesc('seleccion')->get();
        }

        public function updatedCompradorid(): void
        {
            $comprador = \App\Models\Entidad::select('tipo_operacion', 'porcentaje_comision', 'cuit', 'tipo_entidad_id')->find($this->compradorid);
            $this->tipocomprador = substr($comprador->tipo_operacion, 0,1);
            $this->porcentajecomprador = $comprador->porcentaje_comision;
            $this->observacion = $comprador->tipo_entidad_id == 1 ? 'CUIT: '. $comprador->cuit : $this->observacion;
        }

        public function updatedVendedorid(): void
        {
            $vendedor = \App\Models\Entidad::select('tipo_operacion', 'porcentaje_comision', 'cuit', 'tipo_entidad_id')->find($this->vendedorid);
            $this->tipovendedor = substr($vendedor->tipo_operacion, 0,1);
            $this->porcentajevendedore = $vendedor->porcentaje_comision;
            $this->observacion = $vendedor->tipo_entidad_id == 1 ? 'CUIT: '. $vendedor->cuit : $this->observacion;
        }

        // al ingresar o modificar valor de tipo de cambio
        public function updatedTipocambio(): void
        {
            $this->validate([
                                'importe' => ['required', 'numeric:min(0.01):decimal(0,2)'],
                                'tipocambio' => ['required', 'numeric:min(0.01):decimal(0,2)'],
                            ],
                            [
                                'importe' => 'Solo números',
                                'tipocambio' => 'Solo números',
                            ]);

            if ($this->importe != '' && $this->tipocambio != '') {
  //              $importeaux    = str_replace(',', '.', $this->importe);
    //            $tipocambioaux = str_replace(',', '.', $this->tipocambio);
                $this->equivalenteaux     = $this->importe *  $this->tipocambio;
                $this->equivalente     = round($this->equivalenteaux, 2);

                // $this->equivalente =  $this->equivalenteaux ; //number_format($this->equivalenteaux, 2, ',', '.');
                $this->comisionVendedor();
                $this->comisionComprador();
            }
        }
        // para ejecutar el enter en tipo de cambio
        public function entertipocambio()
        {
            $this->updatedTipocambio();
        }


        // vendedor
        // Si modifica el porcentaje de la comisión
        public function updatedPorcientovendedor()
        {
            $valorporciento = 0;
            $valorporciento = $this->porcientovendedor / 1000;
            $this->porcentajevendedore = $this->porcientovendedor;

            if ($this->equivalente != 0) {
                //$this->comisionvendedoraux = $this->equivalenteaux * $valorporciento;
                $this->comisionVendedor();
            }
        }

        // comprador
        // si modifica el porcentaje de la comisión
        public function updatedPorcientocomprador()
        {
            $valorporciento = 0;
            $valorporciento = $this->porcientocomprador / 1000;
            $this->porcentajecomprador = $this->porcientocomprador;

            if ($this->equivalenteaux != 0) {
                //$this->comisioncompradoraux = $this->equivalenteaux * $valorporciento;
                $this->comisionComprador();
            }
        }

        // vendedor
        // comisión en pesos
        public function comisionVendedor()
        {
            if ($this->equivalenteaux != 0  && $this->porcentajevendedore != 0) {
                $this->comisionvendedoraux = ($this->equivalenteaux * $this->porcentajevendedore) /1000;
                //$this->comisionvendedor = number_format($this->comisionvendedoraux, 2); //number_format($this->comisionvendedoraux, 2, ',', '.');
                $this->comisionvendedor = round($this->comisionvendedoraux, 2); //number_format($this->comisionvendedoraux, 2, ',', '.');

                $this->ftotalminuta();
            }
        }

        // comprador
        // comisiòn en pesos
        public function comisionComprador()
        {
            if ($this->equivalenteaux != 0  && $this->porcentajecomprador != 0) {
                $this->comisioncompradoraux = ($this->equivalenteaux * $this->porcentajecomprador) /1000;
                $this->comisioncomprador = round($this->comisioncompradoraux, 2); //number_format($this->comisioncompradoraux, 2, ',', '.');
                $this->ftotalminuta();
            }
        }

        // total minuta
        // tiene que estar al menos una comisión
        public function ftotalminuta()
        {
            $this->totalminutaaux = $this->comisioncompradoraux + $this->comisionvendedoraux;
            $this->totalminuta    =  round($this->totalminutaaux, 2); //number_format($this->totalminutaaux, 2, ',', '.');
        }

        public function cancel(): void
        {
            $this->reset();
            $this->redirectRoute('minutas.boletos.index', navigate: true);
        }

        public function dehydrate()
        {
            //$this->estadoid = 1;
            //dd(now());
            //Carbon::parse($minuta->fecha)->format('d/m/Y')
            $this->fechaboleto =  date('Y-m-d');;
        }

        // Grabar la minuta
        public function grabarMinutaBoleto()
        {
            $this->maximo = \App\Models\Minuta::maxboletocambio()->max('numero') + 1;
            //'importe' =>['required', 'regex:/^\d+(,\d+)?$/'],
            //'regex:/^\d{1,3}(\.\d{3})*(,\d{1,2})?$/'
            $this->validate([
                                'estadoid' => ['required', 'exists:estados,id'],
                                'fechaboleto' => ['required'],
                                'compradorid' => ['required', 'exists:entidads,id'],
                                'vendedorid' => ['required', 'exists:entidads,id'],
                                'monedaid' =>['required', 'exists:monedas,id'],
                                'valorid' =>['required', 'exists:valors,id'],
                                'claseid' =>['required', 'exists:clases,id'],
                                'importe' =>['required', 'regex:/^[\d.]+$/'],
                                'tipocambio' =>['required', 'regex:/^[\d.]+$/'],
                                'referenciaid' => ['required'],
                                'observacion' => ['nullable'],
                            ],
                            [
                                'estadoid' => 'Es requerido',
                                'fechaboleto' => 'Es requerido',
                                'compradorid' => 'Es requerido',
                                'compradorid.exists' => 'no es una entidad',
                                'vendedorid' => 'Es requerido',
                                'monedaid' => 'Es requerido',
                                'valorid' => 'Es requerido',
                                'claseid' => 'Es requerido',
                                'importe.redex' => 'Solo números',
                                'importe' => 'Es requerido',
                                'tipocambio' => 'Es requerido',
                                'tipocambio.redex' => 'Solo números',
                                'referenciaid' => 'Es requerido',
                            ]);
            // cambiar los importes
            //  ver total de la minuta si se graba o suma las dos
            // ver el vendedor
            // ver período

            // importe
            /*
                        $importeaux = str_replace('.', '', $this->importe);
                        $importeaux = str_replace(',', '.', $importeaux);
                        // tipo de cambio
                        $tipocambioaux = str_replace('.', '', $this->tipocambio);
                        $tipocambioaux = str_replace(',', '.', $tipocambioaux);
                        //equivalente
                        $this->equivalente = str_replace('.', '', $this->equivalente);
                        $this->equivalente = str_replace(',', '.', $this->equivalente);
                        // por comisión
                        $this->porcentajevendedore = str_replace('.', '', $this->porcentajevendedore);
                        $this->porcentajevendedore = str_replace(',', '.', $this->porcentajevendedore);
                        // importe comision
                        $this->comisionvendedor = str_replace('.', '', $this->comisionvendedor);
                        $this->comisionvendedor = str_replace(',', '.', $this->comisionvendedor);
                        // por comisión
                        $this->porcentajecomprador = str_replace('.', '', $this->porcentajecomprador);
                        $this->porcentajecomprador = str_replace(',', '.', $this->porcentajecomprador);
                        // importe comision
                        $this->comisioncomprador = str_replace('.', '', $this->comisioncomprador);
                        $this->comisioncomprador = str_replace(',', '.', $this->comisioncomprador);


                        if ( $this->porcentajevendedore == '')
                        {
                            $this->porcentajevendedore = 0;
                        }
                        dd($this->porcentajevendedore);
            */
            DB::transaction(function () {
                try {
                    $cargo = \App\Models\Minuta::create([
                                                   'numero' => $this->maximo,
                                                   'estado_id' => $this->estadoid,
                                                   'producto_id' => 1,
                                                   'fecha' => $this->fechaboleto,
                                                   'comprador_id' => $this->compradorid,
                                                   'vendedor_id' => $this->vendedorid,
                                                   'moneda_id' => $this->monedaid,
                                                   'valor_id' => $this->valorid,
                                                   'clase_id' => $this->claseid,
                                                   'importe' => $this->importe,
                                                   'tipo_cambio' => $this->tipocambio,
                                                   'equivalente' => $this->equivalente,
                                                   'referencia_id' => $this->referenciaid,
                                                   'observacion' => $this->observacion,

                                                   'comision_vendedor' => $this->porcentajevendedore == '' ? 0 : $this->porcentajevendedore,
                                                   'importe_comision_vendedor' => $this->comisionvendedor == '' ? 0 :$this->comisionvendedor,
                                                   'comision_comprador' => $this->porcentajecomprador == '' ? 0 : $this->porcentajecomprador,
                                                   'importe_comision_comprador' => $this->comisioncomprador == '' ? 0 : $this->comisioncomprador,

                    ]);
                }
                catch (\Exception $e) {
                    Log::error($e->getMessage());
                }
            });

           $this->dispatch('refreshComponent')->to('pages::minutas.boletos.index');
           $this->reset();
           Flux::modal('minuta-cambio-crear-modal')->close();
        }

    };
?>

<div>
    <flux:modal name="minuta-cambio-crear-modal" class="min-w-[64rem]">
        <form wire:submit="grabarMinutaCambio" class="space-y-6">
            <div>
                <flux:heading class="font-bold bg-red-100 text-center dark:bg-red-800" size="lg">Datos de la Minuta de Cambio
                </flux:heading>
            </div>
<!-- poner la fecha y el número-->
            <flux:card>
                {{-- nro estado y fecha --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Número --}}
                    <div class="w-1/4">
                        <flux:input wire:model="numeroboleto" label="Número" />
                    </div>
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
                        <flux:date-picker type="input" label="Fecha" wire:model="fechaboleto"/>
                    </div>
                </div>
                {{-- 1° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Comprador --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="compradorid" label="Comprador" placeholder="Seleccione un Comprador">
                            @foreach ($this->compradores as $comprador)
                                <flux:select.option value="{{ $comprador->id }}" wire:key="{{ $comprador->id }}">{{ $comprador->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Tipo --}}
                    <div class="w-10">
                        <flux:input wire:model="tipocomprador" class="mt-6" />
                    </div>
                    {{-- Vendedor --}}
                    <div class="w-1/2">
                        <flux:select variant="listbox" searchable wire:model.live="vendedorid" label="Vendedor" placeholder="Seleccione un Vendedor">
                            @foreach ($this->vendedores as $vendedor)
                                <flux:select.option value="{{ $vendedor->id }}" wire:key="{{ $vendedor->id }}">{{ $vendedor->razon_social }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Tipo --}}
                    <div class="w-10">
                        <flux:input wire:model="tipovendedor" class="mt-6" />
                    </div>
                </div>
                <flux:separator class="my-4"/>
                {{-- 2° fila --}}
                <div class="flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Moneda --}}
                    <div class="w-1/3">
                        <flux:select wire:model="monedaid" label="Moneda" placeholder="Seleccione una Moneda">
                            @foreach ($this->monedas as $moneda)
                                @if ($moneda->seleccion == true)  {{ $this->monedaid = $moneda->id}} @endif
                                <flux:select.option value="{{ $moneda->id }}" wire:key="{{ $moneda->id }}">{{ $moneda->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Valor --}}
                    <div class="w-1/3">
                        <flux:select wire:model="valorid" label="Valor" placeholder="Seleccione un Valor">
                            @foreach ($this->valores as $valor)
                                @if ($valor->seleccion == true)  {{ $this->valorid = $valor->id}} @endif
                                <flux:select.option value="{{ $valor->id }}" wire:key="{{ $valor->id }}">{{ $valor->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Clase --}}
                    <div class="w-1/3">
                        <flux:select  wire:model.live="claseid" label="Clase" placeholder="Seleccione una Clase">
                            @foreach ($this->clases as $clase)
                                @if ($clase->seleccion == true)  {{ $this->claseid = $clase->id}} @endif
                                <flux:select.option value="{{ $clase->id }}" wire:key="{{ $clase->id }}">{{ $clase->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                {{-- 3° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Importe --}}
                    <div class="w-1/3">
                        <flux:input wire:model.live="importe" label="Importe" />
                    </div>
                    {{-- Tipo de cambio --}}
                    <div class="w-1/3">
                       <flux:input wire:model.model.live="tipocambio" wire:keydown.enter="entertipocambio" label="Tipo de Cambio" />
                    </div>
                    {{-- equivalente --}}
                    <div class="w-1/3">
                        <flux:input icon="currency-dollar" wire:model="equivalente" readonly label="Equivalente" />
                    </div>
                </div>
                {{-- 4° fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- Referencia --}}
                    <div class="w-full">
                        <flux:select variant="listbox" searchable wire:model="referenciaid" label="Referencia" placeholder="Seleccione una Referencia">
                            @foreach ($this->referencias as $referencia)
                                @if ($referencia->seleccion == true)  {{ $this->referenciaid = $referencia->id}} @endif
                                <flux:select.option value="{{ $referencia->id }}" wire:key="{{ $referencia->id }}">{{ $referencia->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                </div>
                {{-- 5ª fila --}}
                <div class="mt-2 flex w-full flex-row items-start space-x-4 text-left">
                    {{-- observación --}}
                    <div class="w-full">
                        <flux:textarea rows="2" wire:model.enter.live="observacion" label="Observación" />
                    </div>
                </div>
                <flux:card class="mt-2">
                    {{-- 6ª fila --}}
                    <div class="flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Radio porcentaje --}}
                        <div class="w-1/4">
                            <flux:radio.group wire:model.live="porcientovendedor" label="Comisión Vendedor" variant="pills">
                                <flux:radio value="1" label="1 x mil" />
                                <flux:radio value="0.5" label="1/2 x mil" />
                                <flux:radio value="0.25" label="1/4 x mil" />
                            </flux:radio.group>
                        </div>
                        {{-- comisiónr --}}
                        <div class="w-1/5">
                            <flux:input wire:model="porcentajevendedore" label="% 0/00" />
                        </div>
                        {{-- comisión --}}
                        <div class="w-1/5">
                            <flux:input wire:model="comisionvendedor" label="Comisión $" />
                        </div>
                    </div>

                    <hr class="my-4" style="border: none; height: 2px; background-color: #333; width: 70%;">
                    {{-- 7ª fila --}}
                    <div class="mt-4 flex w-full flex-row items-start space-x-4 text-left">
                        {{-- Radio porcentaje --}}
                        <div class="w-1/4">
                            <flux:radio.group wire:model.live="porcientocomprador" label="Comisión Comprador" variant="pills">
                                <flux:radio value="1" label="1 x mil" />
                                <flux:radio value="0.5" label="1/2 x mil" />
                                <flux:radio value="0.25" label="1/4 x mil" />
                            </flux:radio.group>
                        </div>
                        {{-- comisión --}}
                        <div class="w-1/5">
                            <flux:input wire:model="porcentajecomprador" label="% 0/00" />
                        </div>
                        {{-- Clase --}}
                        <div class="w-1/5">
                            <flux:input wire:model="comisioncomprador" label="Comisión $" />
                        </div>
                        {{-- Total Boleto --}}
                        <div class="w-1/5 -mt-16">
                            <flux:input wire:model="totalminuta" label="Total Minuta" />
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
                <flux:button wire:click="grabarMinutaBoleto()" variant="primary"
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
