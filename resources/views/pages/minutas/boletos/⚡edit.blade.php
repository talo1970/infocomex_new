<?php

    use Livewire\Attributes\On;
    use Livewire\Attributes\Computed;
    use Livewire\Component;


    new class extends Component {

        public $minuta;

        public $numeroboleto;
        public $estadoid;
        public $fechaboleto;
        public $compradorid;
        public $tipocomprador;
        public $vendedorid;
        public $tipovendedor;
        public $monedaid;
        public $valorid;
        public $claseid;
        public $referenciaid;

        public $importe;
        public $tipocambio;
        public $equivalente;

        public $observacion;
        public $porcientovendedor;
        public $porcentajevendedore;
        public $comisionvendedor;
        public $porcientocomprador;
        public $porcentajecomprador;
        public $comisioncomprador;
        public $totalminuta;

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

        #[On('minutaEdit')]
        public function minutaEdit(\App\Models\Minuta $minuta): void
        {
            $this->minuta = $minuta;

            $this->numeroboleto = $this->minuta->numero;
            $this->estadoid = $this->minuta->estado_id;
            $this->fechaboleto = $this->minuta->fecha;
            $this->compradorid = $this->minuta->comprador_id;
            $this->tipocomprador = substr($this->minuta->comprador->tipo_operacion, 0,1);
            $this->vendedorid = $this->minuta->vendedor_id;
            $this->tipovendedor = substr($this->minuta->vendedor->tipo_operacion, 0,1);
            $this->monedaid = $this->minuta->monedi_id;
            $this->valorid = $this->minuta->valor_id;
            $this->claseid = $this->minuta->clase_id;
            $this->referenciaid = $this->minuta->referencia_id;
            $this->importe = $this->minuta->importe;
            $this->tipocambio = $this->minuta->tipo_cambio;
            $this->equivalente = $this->minuta->equivalente;

            $this->observacion = $this->minuta->observacion;
            $this->porcientovendedor = $this->minuta->comision_vendedor;
            $this->porcentajevendedore = $this->minuta->comision_vendedor;
            $this->comisionvendedor = $this->minuta->importe_comision_vendedor;
            $this->porcientocomprador = $this->minuta->comision_comprador;
            $this->porcentajecomprador = $this->minuta->comision_comprador;
            $this->comisioncomprador = $this->minuta->importe_comision_comprador;
            $this->totalminuta = $this->minuta->importe_comision_vendedor +  $this->minuta->importe_comision_comprador;





        }



    };
?>

<div>
    <flux:modal name="minuta-boleto-edit" class="min-w-[64rem]">
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
                                <flux:select.option value="{{ $moneda->id }}" wire:key="{{ $moneda->id }}">{{ $moneda->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Valor --}}
                    <div class="w-1/3">
                        <flux:select wire:model="valorid" label="Valor" placeholder="Seleccione un Valor">
                            @foreach ($this->valores as $valor)
                                <flux:select.option value="{{ $valor->id }}" wire:key="{{ $valor->id }}">{{ $valor->nombre }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    {{-- Clase --}}
                    <div class="w-1/3">
                        <flux:select  wire:model.live="claseid" label="Clase" placeholder="Seleccione una Clase">
                            @foreach ($this->clases as $clase)
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

    </flux:modal>
</div>
